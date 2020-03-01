<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* @IsGranted("ROLE_ADMIN")
*/
class UserController extends AbstractController
{
    
    /**
     * @Route("/utilisateurs", name="user_list")
     */
    public function user_list()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
    
    /**
     * @Route("/edition-utilisateur/{id}", name="user_edit")
     */
    public function user_edit($id, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {

        if ($id > 0)
            $user = $manager->getRepository(User::class)->find($id);
        else
            $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $user->setRoles(["ROLE_ADMIN"]);

            // Enregistre le membre en base
            $manager->persist($user);
            $manager->flush();
            
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $user->getId() ? $user->getId() : 0
        ]);
    }

}