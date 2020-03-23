<?php

namespace App\Controller;

use WhichBrowser;
use App\Entity\Answer;
use App\Entity\Survey;
use App\Form\AnswerType;
use App\Form\SurveyType;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
use App\Repository\PropositionRepository;
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
     * @Route("/editionsondage/{id}", name="survey_edit")
     */
    public function survey_edit($id, Request $request, EntityManagerInterface $manager)
    {
        
        if ($id > 0)
            $survey = $manager->getRepository(Survey::class)->find($id);
        else
            $survey = new Survey();
        
        $proposition = new Proposition();
        $technicalComponent = new TechnicalComponent();
        $survey->getPropositions()->add($proposition);
        $survey->getTechnicalComponents()->add($technicalComponent);
        
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $proposition->setSurvey($survey);
            $technicalComponent->setSurvey($survey);
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
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer, array(
            'survey' => $survey->getId(),
            'multiple' => $survey->getMultiple(),
        ));
        return $this->render('survey/show.html.twig', [
            'controller_name' => 'SurveyController',
            'form' => $form->createView(),
            'survey' => $survey,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="survey_delete")
     */
    public function survey_delete($id, EntityManagerInterface $manager)
    {
        $survey = $manager->getRepository(Survey::class)->find($id);
        $manager->remove($survey);
        $manager->flush();    
        return $this->redirectToRoute('survey_list', [
            'id' => $survey->getId(),
        ]);
    }
}