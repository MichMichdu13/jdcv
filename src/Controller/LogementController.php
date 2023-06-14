<?php

namespace App\Controller;

use App\Entity\ImgLogement;
use App\Entity\Logement;
use App\Entity\Profil;
use App\Entity\TagsToLogement;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\LogementRepository;
use App\Repository\ProfilRepository;
use App\Repository\StyleRepository;
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

    #[Route('api/public/logement/search', name: 'getlogements', methods: ['POST'])]
    public function searchLogment(Request $request, LogementRepository $logementRepository, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);

        $styleCriteria = $data['style'] ?? null;
        $eventCriteria = $data['event'] ?? null;
        $tagCriteria = $data['tag'] ?? null;
        $department = $data['departement'] ?? null; // new line
        $nbPersonne = $data['nbPersonne'] ?? null;
        $page = $data['page'] ?? 1;
        $startDate = isset($data['startDate']) ? new \DateTime($data['startDate']) : null;
        $endDate = isset($data['endDate']) ? new \DateTime($data['endDate']) : null;

        if ($startDate === null && $endDate !== null) {
            $startDate = $endDate; // Utilisez la même date pour startDate
        } elseif ($startDate !== null && $endDate === null) {
            $endDate = $startDate; // Utilisez la même date pour endDate
        }

        // add department to the method call
        $logements = $logementRepository->findByCriteria($styleCriteria, $eventCriteria, $tagCriteria, $startDate, $endDate, $department, $nbPersonne, $page);

        if($logements){
            $jsonLogement = $serializer->serialize($logements, 'json',['groups' => 'getLogement']);
            return new JsonResponse($jsonLogement, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('api/public/logement/{id}', name: 'getlogement', methods: ['GET'])]
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
    public function createLogement(Request $request,EventRepository $eventRepository,TagsRepository $tagsRepository, StyleRepository $styleRepository,SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, Security $security, ProfilRepository $profilRepository): JsonResponse
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

        $event = $content['event'] ?? null;
        if($event !== null){
            $eventLogement =  $eventRepository->findOneBy(['name' => $event]);
            $logement->setEvent($eventLogement);
        }else{
            return new JsonResponse(['message' => 'Bad event data'], Response::HTTP_BAD_REQUEST);
        }
        $style = $content['style'] ?? null;
        if($event !== null){
            $styleLogement =  $styleRepository->findOneBy(["name" => $style]);
            $logement->setStyle($styleLogement);
        }else{
            return new JsonResponse(['message' => 'Bad style data'], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($logement);
        $em->flush();


        $imgs = $content['Imgs'] ?? [];
        $firstImage = true;
        foreach ($imgs as $imgBase64) {
            $imgBase64 = preg_replace('/data:image\/png;base64,/', '', $imgBase64);
            $imageData = base64_decode($imgBase64);
            $tmpPath = tempnam(sys_get_temp_dir(), 'base64decoded');
            file_put_contents($tmpPath, $imageData);
    
            $fileName = uniqid() . '.png';
            $filePath = $this->getParameter('images_directory') . '/' . $fileName;
            rename($tmpPath, $filePath);
    
            $image = new ImgLogement();
            $image->setFilename($fileName);
            $image->setLogement($logement);
            $image->setImgMain($firstImage); // Définition de la valeur imgMain
    
            $em->persist($image);
            $em->flush();
    
            $firstImage = false;
        }

        $jsonLogement = $serializer->serialize($logement, 'json',['groups' => 'getLogement']);

        $location = $urlGenerator->generate('getlogement', ['id' => $logement->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonLogement, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/public/event/{name}/logements/count', name: 'count_logements_by_event', methods: ['GET'])]
    public function countLogementsByEvent(string $name, LogementRepository $logementRepository): JsonResponse
    {
        $count = $logementRepository->countByEventName($name);

        return new JsonResponse(['count' => $count]);
    }
    #[Route('/api/style/{name}/logements/count', name: 'count_logements_by_style', methods: ['GET'])]
    public function countLogementsByStyle(string $name, LogementRepository $logementRepository): JsonResponse
    {
        $count = $logementRepository->countByStyleName($name);

        return new JsonResponse(['count' => $count]);
    }

}
