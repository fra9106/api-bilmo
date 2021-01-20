<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/phones")
 */

class PhoneController extends AbstractController
{
    /**
     *  @Route("/", name="list_phone", methods={"GET"})
     */
    public function listPhone(PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $phones = $phoneRepository->findAll();
        $data = $serializer->serialize($phones, 'json',['groups' => 'phone:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }

    /**
     * @Route("/{id}", name="show_phone", methods={"GET"})
     */
    public function showPhone(Phone $phone, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $phone = $phoneRepository->find($phone->getId());
        $data = $serializer->serialize($phone, 'json',['groups' => 'phone:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }
}
