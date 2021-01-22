<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/shops")
 */

class ShopController extends AbstractController
{
    /**
     *  @Route("/", name="list_shops", methods={"GET"})
     */
    public function listShops(ShopRepository $shopRepository, SerializerInterface $serializer)
    {
        $shops = $shopRepository->findAll();
        $data = $serializer->serialize($shops, 'json', ['groups' => 'shop:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }

    /**
     * @Route("/{id}", name="show_shop", methods={"GET"})
     */
    public function showShop(Shop $shop, ShopRepository $phoneRepository, SerializerInterface $serializer)
    {
        $shop = $phoneRepository->find($shop->getId());
        $data = $serializer->serialize($shop, 'json',['groups' => 'shop:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }
}
