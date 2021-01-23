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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Route("/api/users")
*/

class UserController extends AbstractController
{
    /**
    *  @Route("/{page<\d+>?1}", name="list_users", methods={"GET"}, priority= -1)
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
    * @Route("/{id}", name="show_user", methods={"GET"})
    */
    public function showUser(User $user, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $user = $userRepository->find($user->getId());
        $data = $serializer->serialize($user, 'json',['groups' => 'user:read']);
        $response = new JsonResponse($data, 200, [], true);
        
        return $response;
    }
    
    /**
    * @Route("/add", name="add_user", methods={"POST"})
    */
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $json = $request->getContent();
        $userConnected = $this->getUser();
        try {
            $newUser = $serializer->deserialize($json, User::class, 'json');
            $newUser->setCreatedAt(new DateTime());
            $newUser->setShop($userConnected);
            
            $error = $validator->validate($newUser);
            
            if (count($error) > 0) {
                return  $this->json($error, 400);
            }
            
            $em->persist($newUser);
            $em->flush();
            
            $newUser = [
                'status' => 201,
                'message' => 'Le nouvel utilisateur a bien été ajouté !'
            ];
            
            return $this->json($newUser, 201, [], [ 
                'groups' => 'user:read'
                ]);
                
            }catch(NotEncodableValueException $e ) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage() 
                ],400);
            }
        }
        
        /**
        * @Route("/{id}", name="delete_user", methods={"DELETE"})
        */
        public function deleteUser(User $user, EntityManagerInterface $em)
        {
            $this->denyAccessUnlessGranted('CAN_DELETE', $user, "Vous n'êtes pas autorisé à supprimer cet utilisateur");
            $em->remove($user);
            $em->flush();
            return new Response(null, 204);
        }
    }
    