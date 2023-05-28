<?php

namespace App\Controller;

use App\Entity\ImgLogement;
use App\Entity\Logement;
use App\Entity\Profil;
use App\Entity\TagsToLogement;
use App\Entity\User;
use App\Repository\LogementRepository;
use App\Repository\ProfilRepository;
use App\Repository\TagsRepository;
use App\Repository\TagsToLogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;


class LogementController extends AbstractController
{

    #[Route('api/logement/{id}', name: 'getlogement', methods: ['GET'])]
    public function findLogement(int $id, SerializerInterface $serializer,LogementRepository $logementRepository  ): JsonResponse
    {
        $logement = $logementRepository->find($id);
        if($logement){
            $jsonLogement = $serializer->serialize($logement, 'json',['groups' => 'getLogement']);
            return new JsonResponse($jsonLogement, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('/api/logement', name:"createlogement", methods: ['POST'])]
    public function createLogement(Request $request,TagsRepository $tagsRepository, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, Security $security, ProfilRepository $profilRepository): JsonResponse
    {
        $user = $security->getUser()->getUserIdentifier();
        $profile = $profilRepository->findOneBy(array('email' => $user));

        $logement = $serializer->deserialize($request->getContent(), Logement::class, 'json');
        $logement->setUser($profile->getUser());
        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();

        // Récupération de l'idAuthor. S'il n'est pas défini, alors on met -1 par défaut.
        $tags = $content['Tags'] ?? [];
        foreach ($tags as $tag) {
            $tagToLogement = new TagsToLogement();
            $tagToLogement->setLogement($logement);
            $tagToLogementEntity = $tagsRepository->findOneBy(['tag' => $tag]);

            if ($tagToLogementEntity) {
                $tagToLogement->setTag($tagToLogementEntity);
            }
            $em->persist($tagToLogement);
            $em->flush();
        }
        $em->persist($logement);
        $em->flush();

        $imgs = $content['Imgs'] ?? [];
        foreach ($imgs as $img) {

            // Decode the image from base64 and save it to a temporary file
            $imageData = base64_decode($img['imgBase64']);
            $tmpPath = tempnam(sys_get_temp_dir(), 'base64decoded');
            file_put_contents($tmpPath, $imageData);

            // Create a unique name for the file and move it to the public/images directory
            $fileName = uniqid() . '.png';  // Replace 'png' with the correct extension
            $filePath = $this->getParameter('images_directory') . '/' . $fileName;
            rename($tmpPath, $filePath);

            // Create a new Image entity and set its properties
            $image = new ImgLogement();
            $image->setFilename($fileName);
            $image->setImgMain($img['imgMain']);
            $image->setLogement($logement);
            // You might need to fetch the related housing and set it like this:
            // $image->setHousing($housing);

            // Persist and flush the new Image entity
            $em->persist($image);
            $em->flush();
        }

        $jsonLogement = $serializer->serialize($logement, 'json',['groups' => 'getLogement']);

        $location = $urlGenerator->generate('getlogement', ['id' => $logement->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonLogement, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/logement/{id}', name: 'deletelogement', methods: ['DELETE'])]
    public function deleteLogement(Logement $logement, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($logement);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/books/{id}', name:"updateBook", methods:['PUT'])]

    public function updateLogement(Request $request, SerializerInterface $serializer, Profil $profil, EntityManagerInterface $em, AuthorRepository $authorRepository): JsonResponse
    {
        $updatedlogement = $serializer->deserialize($request->getContent(),
            Profil::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $profil]);
        $em->persist($updatedlogement);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
