<?php

namespace App\Controller;

//use WhichBrowser;
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
use App\Repository\SurveyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\WhichBrowserService;

class AnswerController extends AbstractController
{
    /**
     * @Route("/reponse/liste/{id}", name="answer_list")
      * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
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
    * @Route("/sondage/consultation/{id}", name="survey_show") 
    * @Route("/repondre/sondage/{id}", name="answer_survey")
    * @param $_route
     */
    public function answer($_route, $id, Request $request, EntityManagerInterface $manager, WhichBrowserService $whichBrowserService)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ['type' => 'ASC']);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer, array(
            'survey' => $survey->getId(),
            'multiple' => $survey->getMultiple(),
            'need_component' => $survey->getNeedComponent(),
            'show_assistive' => $survey->getShowAssistive(),
            'propositions' => $survey->getPropositions(),
            'categories' => $categories,
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setSurvey($survey);
            $answer->setCreatedat(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $answer->setAcceptedat(new \DateTime('now'));
            $whichBrowserService->getTechnicalDatas($answer);
            
            if ($survey->getMultiple() > 0) {
                foreach ($form["propositions"]->getData() as $proposition)
                    $answer->addProposition($proposition);
            } else {
                $proposition = $this->getDoctrine()->getRepository(Proposition::class)->find($form['propositions']->getData());
                $answer->setPropositions($proposition);
            }

            if ($survey->GetShowAssistive() > 1) {
                foreach ($form["assistives"]->getData() as $assistive)
                    $answer->addAssistive($assistive);
            }

            $manager->persist($answer);
            $manager->flush();

            if ($_route=="survey_show")
                return $this->redirectToRoute('survey_list');
            else
                return $this->redirectToRoute('answer_thank_you');
        
        }
        
        $render = $_route=="survey_show" ? 'survey/show.html.twig' : 'answer/survey.html.twig';
        return $this->render($render, [
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
    * @Route("/{entity}/{action}/composant-technique/{id}", name="answer_technicalcomponent") 
     */
    public function technicalcomponent($entity, $action, $id)
    {
        $technicalComponent = $this->getDoctrine()->getRepository(TechnicalComponent::class)->find($id);
        return $this->render('answer/technicalcomponent.html.twig', [
            'controller_name' => 'AnswerController',
            'technicalComponent' => $technicalComponent,
            'entity' => $entity,
            'action' => $action,
        ]);
    }
}