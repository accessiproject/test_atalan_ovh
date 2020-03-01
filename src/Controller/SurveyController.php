<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SurveyController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="survey_default")
     * @Route("/liste-des-sondages", name="survey_list")
     */
    public function survey_list()
    {
        return $this->render('survey/list.html.twig', [
            'controller_name' => 'SurveyController',
        ]);
    }
}
