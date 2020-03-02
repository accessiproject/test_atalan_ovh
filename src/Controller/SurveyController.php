<?php

namespace App\Controller;

use App\Entity\Survey;
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
}
