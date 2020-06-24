<?php

namespace App\Controller;

//dependency injection
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


//Security annotations on a controller class to prevent access to all users with ROLE_ADMIN 
/**
* @IsGranted("ROLE_ADMIN")
*/
class UserController extends AbstractController
{
    
    //method to display the list of all user accounts
    /**
     * @Route("/utilisateurs", name="user_list")
     */
    public function user_list()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll(); //request to retrieve all users
        return $this->render('user/list.html.twig', [ //return to the template
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    
    //method to display the creation (or modification) of a user account
    /**
     * @Route("/utilisateur/edition/{id}", name="user_edit")
     */
    public function user_edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {

        
        if ($id > 0) //if there is a modification of a user account 
            $user = $manager->getRepository(User::class)->find($id); //request to retrieve a user account
        else
            $user = new User(); //else there is a creation of a user account 

        //to use a form UserType.php
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        //if the user submits the form
        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $passwordEncoder->encodePassword($user, $user->getPassword()); //Encode the password
            $user->setPassword($password); //setter the password of user

            $user->setRoles(["ROLE_ADMIN"]); //setter the role of user with ROLE_ADMIN

            $user->updatedTimestamps(); //to add a creation date and an update date
            
            //save user account in database
            $manager->persist($user);
            $manager->flush();
            
            return $this->redirectToRoute('user_list'); //redirect to the list of users
        }
        return $this->render('user/edit.html.twig', [ //return to the template
            'form' => $form->createView(),
            'id' => $user->getId() ? $user->getId() : 0
        ]);
    }

    //method to delete a user account
    /**
     * @Route("/utilisateur/suppression/{id}", name="user_delete")
     */
    public function user_delete($id, EntityManagerInterface $manager)
    {
        $user = $manager->getRepository(User::class)->find($id); //request to retrieve a user account
        $manager->remove($user); //delete the user account
        $manager->flush();
        return $this->redirectToRoute('user_list', [ //redirect to the list of users
            'id' => $user->getId(),
        ]);
    }
}