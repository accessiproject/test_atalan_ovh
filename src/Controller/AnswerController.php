<?php

namespace App\Controller;

use WhichBrowser;
use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Assistive;
use App\Entity\Category;
use App\Entity\Survey;
use App\Form\AnswerType;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
use App\Repository\AnswerRepository;
use App\Repository\PropositionRepository;
use App\Repository\AssistiveRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AnswerController extends AbstractController
{
    /**
     * @Route("/reponse/liste/{id}", name="answer_list")
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
     * @Route("/reponse/suppression/{id}", name="answer_delete")
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

    /**
     * @Route("/reponse/test/{id}", name="answer_answer")
     */
    public function answer_answer($id, Request $request, EntityManagerInterface $manager)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ['type' => 'ASC']);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer, array(
            'survey' => $survey->getId(),
            'multiple' => $survey->getMultiple(),
            'propositions' => $survey->getPropositions(),
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setSurvey($survey);
            $answer->setCreatedat(new \DateTime('now'));
            $answer->setAcceptedat(new \DateTime('now'));
            $result = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            $answer->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            $answer->setDeviceType($result->device->type);
            $answer->setDeviceIdentifier($result->device->identifier);
            $answer->setDeviceManufacturer($result->device->manufacturer);
            $answer->setDeviceModel($result->device->model);
            $answer->setOsName($result->os->name);
            $answer->setOsVersion($result->os->version->toString());
            $answer->setBrowserName($result->browser->name);
            $answer->setBrowserVersion($result->browser->version->toString());
            if ($survey->getMultiple() > 0) {
                foreach ($form["propositions"]->getData() as $proposition)
                    $answer->addProposition($proposition);
            } else {
                $proposition = $this->getDoctrine()->getRepository(Proposition::class)->find($form['propositions']->getData());
                $answer->setPropositions($proposition);
            }
            
            foreach ($form["assistives"]->getData() as $assistive)
                $answer->addAssistive($assistive);
            
            $manager->persist($answer);
            $manager->flush();
            $this->addFlash('success', 'Votre réponse a bien été enregistrée.');
            return $this->redirectToRoute('answer_thank_you');
        }
        return $this->render('answer/answer.html.twig', [
            'controller_name' => 'AnswerController',
            'form' => $form->createView(),
            'survey' => $survey,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/reponse/remerciement", name="answer_thank_you")
     */
    public function answer_thank_you()
    {
        return $this->render('answer/thank_you.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    /**
     * @Route("/composant-technique//{id}", name="answer_technicalcomponent")
     */
    public function answer_technicalcomponent($id)
    {
        $technicalComponent = $this->getDoctrine()->getRepository(TechnicalComponent::class)->find($id);
        return $this->render('answer/technicalcomponent.html.twig', [
            'controller_name' => 'AnswerController',
            'technicalComponent' => $technicalComponent,
        ]);
    }
}