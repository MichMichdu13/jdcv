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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends AbstractController
{
    #[Route('api/profile/{id}', name: 'getProfile', methods: ['GET'])]
    public function findProfile(int $id, SerializerInterface $serializer,ProfilRepository $profilRepository  ): JsonResponse
    {
        $profile = $profilRepository->find($id);
        if($profile){
            $jsonProfile = $serializer->serialize($profile, 'json',['groups' => 'getProfile']);
            return new JsonResponse($jsonProfile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('/api/register', name:"createProfile", methods: ['POST'])]
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

        try {
            $em->persist($profile);
            $em->persist($user);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to save data.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $jsonProfile = $serializer->serialize($profile, 'json');

        $location = $urlGenerator->generate('getProfile', ['id' => $profile->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonProfile, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Route('/api/profile/{id}', name: 'deleteProfile', methods: ['DELETE'])]
    public function deleteProfile(Profil $profil, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($profil);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/books/{id}', name:"updateBook", methods:['PUT'])]

    public function updateProfile(Request $request, SerializerInterface $serializer, Profil $profil, EntityManagerInterface $em, AuthorRepository $authorRepository): JsonResponse
    {
        $updatedProfile = $serializer->deserialize($request->getContent(),
            Profil::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $profil]);
        $em->persist($updatedProfile);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
