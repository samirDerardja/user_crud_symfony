<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use DateTimeInterface;
use App\Service\UserService;
use Symfony\Component\Form\Forms;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use App\Service\UserService as ServiceUser;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


 
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

   
    /**
     * @Route("/user/{page<\d+>?1}", name="user", methods={"GET"})
     */
    public function index(Request $request , UserRepository $userRepo,  SerializerInterface $serializer, PaginatorInterface $paginator )
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        
        // Get some repository of data, in our case we have an Appointments entity
        $usersRepository = $em->getRepository(User::class);
                
        // Find all the data on the Appointments table, filter your query as you need
        $allUsersQuery = $usersRepository->createQueryBuilder('p')
            ->where('p.birthDate != :birthDate')
            ->setParameter('birthDate', 'ok')
            ->getQuery();
         
        // Paginate the results of the query
        $users = $paginator->paginate(
            // Doctrine Query, not results
            $allUsersQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        
        return $this->render('user/index.html.twig', array
        ('users' => $users));
        
    }

    /**
     * @Route("/api/listeUsers", name="listeUsers", methods={"GET"})
     */

    // public function listeUsers(UserRepository $userRepo, SerializerInterface $serializer)
    // {

    //     $users = $userRepo->findAll();
    //     $data = $serializer->serialize($users, 'json');

    //     return new Response($data, 200, [
    //         'Content-Type' => 'application/json'
    //     ]);
    
    // } 


    /*
    * @Route("/new", name="new_user")
    * @ParamConverter("start", options={"format": "!Y-m-d"})
    * @ParamConverter("end", options={"format": "!Y-m-d"})
    * Method ({"GET", "POST"})
    */

    public function newUser(Request $request , SerializerInterface $serializer) : Response
    {
        $user = new User();

        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 
       
        $dateNow = new DateTime();
        dump($dateNow);

        $jsonContent = $serializer->serialize($user, 'json');
        dump($jsonContent);

        $data = $user->getBirthDate();
        
        $user->setAgeUser($dateNow->diff($data, true)-> y);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        }

        public function viewUsers($id)
    {
        $user = $this->getDoctrine()
            ->getRepository('App\Entity\User')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'There are no users with the following id: ' . $id
            );
        }

        return $this->render(
            'index.html.twig',
            array('users' => $users)
        );
    }



        /**
     * @Route("/{id}/edit/", name="edit", methods={"GET","POST"})
     */
    
        public function edit(Request $request, User $user,SerializerInterface $serializer): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); 
       
        $dateNow = new DateTime();
        dump($dateNow);

        $jsonContent = $serializer->serialize($user, 'json');
        dump($jsonContent);

        $data = $user->getBirthDate();
        
        $user->setAgeUser($dateNow->diff($data, true)-> y);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    } 
    
    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
       
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
    
        return $this->redirectToRoute('user');
    }

}
