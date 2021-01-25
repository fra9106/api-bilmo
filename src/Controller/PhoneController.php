<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

/**
 * @Route("/api/phones")
 */

class PhoneController extends AbstractController
{
    /**
     *  @Route("/{page<\d+>?1}", name="list_phone", methods={"GET"}, priority= -1)
     */
    public function listPhone(Request $request,PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        $limit = 5;
        $phones = $phoneRepository->findAllPhones($page, $limit);
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

    /**
    * @Route("/addphone", name="add_phone", methods={"POST"})
    * @IsGranted("ROLE_ADMIN", statusCode=403, message="Vous n'avez pas les droits administrateur pour ajouter un produit !")
    */
    public function addPhone(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $json = $request->getContent();
        try {
            $newPhone = $serializer->deserialize($json, Phone::class, 'json');
            
            $error = $validator->validate($newPhone);
            
            if (count($error) > 0) {
                return  $this->json($error, 400);
            }
            
            $entityManager->persist($newPhone);
            $entityManager->flush();
            
            $newUser = [
                'status' => 201,
                'message' => 'Le nouveau produit a bien été ajouté !'
            ];
            
            return $this->json($newUser, 201, [], [ 
                'groups' => 'phone:read'
                ]);
                
            }catch(NotEncodableValueException $e ) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage() 
                ],400);
            }
        }

    /**
     * @Route("/{id}", name="update_phone", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Vous n'avez pas les droits administrateur pour modifier ce produit !")
     */
    public function updatePhone(Phone $phone, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $updatePhone = $entityManager->getRepository(Phone::class)->find($phone->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                $name = ucfirst($key);
                $set = 'set'.$name;
                $updatePhone->$set($value);
            }
        }
        $errors = $validator->validate($updatePhone);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json',
                
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Produit modifié !'
        ];
        return new JsonResponse($data);
    }

    /**
        * @Route("/{id}", name="delete_phone", methods={"DELETE"})
        * @IsGranted("ROLE_ADMIN", statusCode=403, message="Vous n'avez pas les droits administrateur pour supprimer ce produit !")
        */
        public function deletePhone(Phone $phone, EntityManagerInterface $entityManager)
        {
            $entityManager->remove($phone);
            $entityManager->flush();
            return new Response(null, 204);
        }
}
