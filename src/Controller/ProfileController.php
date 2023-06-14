<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends AbstractController
{
    #[Route('api/public/profile/{id}', name: 'getProfile', methods: ['GET'])]
    public function findProfile(int $id, SerializerInterface $serializer,ProfilRepository $profilRepository  ): JsonResponse
    {
        $profile = $profilRepository->find($id);
        if($profile){
            $jsonProfile = $serializer->serialize($profile, 'json',['groups' => 'getProfile']);
            return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('/api/public/register', name:"createProfile", methods: ['POST'])]
    public function createProfile(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator,UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $data = $request->getContent();

        if (empty($data)) {
            return new JsonResponse(['error' => 'Data is missing.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $profile = $serializer->deserialize($data, Profil::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid data format.'], Response::HTTP_BAD_REQUEST);
        }
        $content = $request->toArray();
        if(isset($content["password"])){
            $profile->setPassword($userPasswordHasher->hashPassword($profile, "password"));
        }else{
            return new JsonResponse(['error' => 'Invalid data password.'], Response::HTTP_BAD_REQUEST);
        }


        $user = new User();
        $user->setProfile($profile);
        $profile->setUser($user);


        try {
            $em->persist($profile);
            $em->persist($user);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to save data.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $jsonProfile = $serializer->serialize($profile, 'json',['groups' => 'getProfile']);

        $location = $urlGenerator->generate('getProfile', ['id' => $profile->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonProfile, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/profile/{id}', name:"updateProfile", methods: ['PATCH'])]
    public function updateProfile(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ProfilRepository $profilRepository, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $existingProfile = $profilRepository->find($id);

        if (!$existingProfile) {
            return new JsonResponse(['error' => 'Profile not found.'], Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->toArray();

        if (isset($requestData['password'])) {
            $requestData['password'] = $userPasswordHasher->hashPassword($existingProfile, $requestData['password']);
        }

        $updatedProfile = $serializer->denormalize($requestData, Profil::class, 'array', [ObjectNormalizer::OBJECT_TO_POPULATE => $existingProfile]);

        try {
            $em->persist($updatedProfile);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to save data.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $jsonProfile = $serializer->serialize($updatedProfile, 'json',['groups' => 'getProfile']);

        return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
    }


    #[Route('/api/profile/{id}', name: 'deleteProfile', methods: ['DELETE'])]
    public function deleteProfile(Profil $profil, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($profil);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
