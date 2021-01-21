<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api")
 */

class UserController extends AbstractController
{
    /**
     *  @Route("/{page<\d+>?1}", name="list_users", methods={"GET"})
     */
    public function listUser(Request $request, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        $limit = 5;

        $users = $userRepository->findAllUsers($page, $limit);
        $data = $serializer->serialize($users, 'json', ['groups' => 'users-list:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }

    /**
     * @Route("/users/{id}", name="show_user", methods={"GET"})
     */
    public function showUser(User $user, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $user = $userRepository->find($user->getId());
        $data = $serializer->serialize($user, 'json',['groups' => 'user:read']);
        $response = new JsonResponse($data, 200, [], true);

        return $response;
    }

    /**
     * @Route("/users", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $json = $request->getContent();
    
        $newUser = $serializer->deserialize($json, User::class, 'json');
        $newUser->setCreatedAt(new DateTime());
        
        $em->persist($newUser);
        $em->flush();

        $newUser = [
            'status' => 201,
            'message' => 'Le nouvel utilisateur a bien été ajouté !'
        ];
        
        return $this->json($newUser, 201, [], ['groups' => 'user:read']);
    }

    /**
     * @Route("/users/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        return new Response(null, 204);
    }
}
