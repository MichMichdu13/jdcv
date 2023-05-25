<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Repository\ProfilRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TagsController extends AbstractController
{
    #[Route('/tags', name: 'app_tags')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TagsController.php',
        ]);
    }

    #[Route('api/tag/{id}', name: 'findTag', methods: ['GET'])]
    public function findTag(int $id, SerializerInterface $serializer,TagsRepository $tagsRepository): JsonResponse
    {
        $tag = $tagsRepository->find($id);
        if($tag){
            $jsonTag = $serializer->serialize($tag, 'json');
            return new JsonResponse($jsonTag, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/tag', name:"createTags", methods: ['POST'])]
    public function createTag(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {

        $tag = $serializer->deserialize($request->getContent(), Tags::class, 'json');
        $em->persist($tag);
        $em->flush();

        $jsonTags = $serializer->serialize($tag, 'json',['groups' => 'getTags']);

        $location = $urlGenerator->generate('getTags', ['id' => $tag->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonTags, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
