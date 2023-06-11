<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\LogementRepository;
use App\Repository\ProfilRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationController extends AbstractController
{
    #[Route('api/reservation/{id}', name: 'postReservation', methods: ['POST'])]
    public function new(int $id, SerializerInterface $serializer, Security $security, LogementRepository $logementRepository, Request $request, ReservationRepository $reservationRepository, ProfilRepository $profilRepository): JsonResponse
    {
        $logement = $logementRepository->find($id);
        if(!$logement){
            return new JsonResponse(['message' => 'Logement not found'], Response::HTTP_NOT_FOUND);
        }
        $reservation = $serializer->deserialize($request->getContent(), Reservation::class, 'json');
        if(!$reservation){
            return new JsonResponse(['message' => 'Bad reservation data'], Response::HTTP_BAD_REQUEST);
        }
        $reservation->setLogement($logement);
        dd($reservation);
        $reservationRepository->save($reservation);
        $jsonLogement = $serializer->serialize($logement, 'json',['groups' => 'getLogement']);

        return new JsonResponse($jsonLogement, Response::HTTP_OK, [], true);
    }

    #[Route('api/reservation/{id}', name: 'reservation', methods: ['GET'])]
    public function reservation(int $id,Serializer $serializer,Security $security, ReservationRepository $reservationRepository, ProfilRepository $profilRepository): JsonResponse
    {
        $user = $security->getUser()->getUserIdentifier();
        $profile = $profilRepository->findOneBy(array('email' => $user));
        $userResa = $profile->getUser();
        $resa = $reservationRepository->findOneBy(array('id'=>$id));
        $resa->setUser($userResa);
        $reservationRepository->save($resa);
        return new JsonResponse( Response::HTTP_OK, [], true);
    }
}
