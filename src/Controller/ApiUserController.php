<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_users", methods={"GET"})
     */
    public function index(UserRepository $repo, SerializerInterface $serializer)
    {

        $users = $repo->findAll();
        $resultats = $serializer->serialize(
            $users , 'json'
           
        ); 
        return new JsonResponse($resultats, 200, [
        ], true);
    }


    /**
     * @Route("/api/users/{id}", name="api_users_show", methods={"GET"})
     */

    public function show(User $user, SerializerInterface $serializer)
    {
       
        $resultats = $serializer->serialize(
            $user , 'json'

        );
        return new JsonResponse($resultats, Response::HTTP_OK, [], true);
    }

}
