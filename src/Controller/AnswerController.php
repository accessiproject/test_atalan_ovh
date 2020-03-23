<?php

namespace App\Controller;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnswerController extends AbstractController
{
    /**
     * @Route("/reponses/{id}", name="answer_list")
     */
    public function answer_list($id)
    {
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBySurvey($id);
        return $this->render('answer/list.html.twig', [
            'controller_name' => 'AnswerController',
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/sup/{id}", name="answer_delete")
     */
    public function answer_delete($id, EntityManagerInterface $manager)
    {
        $answer = $manager->getRepository(Answer::class)->find($id);
        $manager->remove($answer);
        $manager->flush();    
        return $this->redirectToRoute('answer_list', [
            'id' => $answer->getId(),
        ]);
    }
}
