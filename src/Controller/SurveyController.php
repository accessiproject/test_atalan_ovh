<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\Proposition;
use App\Form\SurveyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @IsGranted("ROLE_ADMIN")
*/
class SurveyController extends AbstractController
{
    /**
     * @Route("/", name="survey_default")
     * @Route("/sondages", name="survey_list")
     */
    public function survey_list()
    {
        $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        return $this->render('survey/list.html.twig', [
            'controller_name' => 'SurveyController',
            'surveys' => $surveys,
        ]);
    }

    /**
     * @Route("/edition/{id}", name="survey_edit")
     */
    public function survey_edit($id, Request $request, EntityManagerInterface $manager)
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey->updatedTimestamps();
            $survey->setClosedat(new \DateTime('now'));

            // Enregistre le sondage en base
            $manager->persist($survey);
            $manager->flush();
            
            return $this->redirectToRoute('survey_list');
        }
        return $this->render('survey/edit.html.twig', [
            'controller_name' => 'SurveyController',
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/consultation/{id}", name="survey_show")
     */
    public function survey_show($id)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        return $this->render('survey/show.html.twig', [
            'controller_name' => 'SurveyController',
            'survey' => $survey,
        ]);
    }

}