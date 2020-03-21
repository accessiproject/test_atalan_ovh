<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\Assistive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @IsGranted("ROLE_ADMIN")
*/
class AssistiveController extends AbstractController
{
    /**
     * @Route("/assistances", name="assistive_list")
     */
    public function assistive_list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('assistive/list.html.twig', [
            'controller_name' => 'AssistiveController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/edition/{id}", name="assistive_edit")
     */
    public function assistive_edit($id, Request $request, EntityManagerInterface $manager)
    {
        
        if ($id > 0)
            $category = $manager->getRepository(Category::class)->find($id);
        else
            $category = new Category();
        
        $assistive = new Assistive();
        $category->getAssistives()->add($assistive);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $assistive->setCategory($category);
            // Enregistre le sondage en base
            $manager->persist($category);
            $manager->flush();
            
            return $this->redirectToRoute('assistive_list');
        }
        return $this->render('assistive/edit.html.twig', [
            'controller_name' => 'AssistiveController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="assistive_delete")
     */
    public function assistive_delete($id, EntityManagerInterface $manager)
    {
        $category = $manager->getRepository(Category::class)->find($id);
        $manager->remove($category);
        $manager->flush();    
        return $this->redirectToRoute('assistive_list', [
            'id' => $category->getId(),
        ]);
    }
}