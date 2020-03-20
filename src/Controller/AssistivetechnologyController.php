<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\AssistiveTechnology;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @IsGranted("ROLE_ADMIN")
*/
class AssistivetechnologyController extends AbstractController
{
    /**
     * @Route("/technologies-assistance", name="assistivetechnology_list")
     */
    public function assistivetechnology_list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('assistivetechnology/list.html.twig', [
            'controller_name' => 'AssistivetechnologyController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/edition/{id}", name="assistivetechnology_edit")
     */
    public function assistivetechnology_edit($id, Request $request, EntityManagerInterface $manager)
    {
        
        if ($id > 0)
            $category = $manager->getRepository(Category::class)->find($id);
        else
            $category = new Category();
        
        $assistiveTechnology = new AssistiveTechnology();
        $category->getAssistiveTechnologies()->add($assistiveTechnology);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $assistiveTechnology->setCategory($category);
            // Enregistre le sondage en base
            $manager->persist($category);
            $manager->flush();
            
            return $this->redirectToRoute('assistivetechnology_list');
        }
        return $this->render('assistivetechnology/edit.html.twig', [
            'controller_name' => 'AssistivetechnologyController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="assistivetechnology_delete")
     */
    public function assistivetechnology_delete($id, EntityManagerInterface $manager)
    {
        $category = $manager->getRepository(Category::class)->find($id);
        $manager->remove($category);
        $manager->flush();    
        return $this->redirectToRoute('assistivetechnology_list', [
            'id' => $category->getId(),
        ]);
    }
}