<?php

namespace App\Controller;

use WhichBrowser;
use App\Entity\Answer;
use App\Entity\Assistive;
use App\Entity\Category;
use App\Entity\Survey;
use App\Form\AnswerType;
use App\Form\SurveyType;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
use App\Repository\AnswerRepository;
use App\Repository\PropositionRepository;
use App\Service\AjaxService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use App\Service\CountryService;


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
    public function survey_show($id, Request $request, EntityManagerInterface $manager)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer, array(
            'survey' => $survey->getId(),
            'multiple' => $survey->getMultiple(),
            'propositions' => $survey->getPropositions(),
            'categories' => $categories,
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
            if ($survey->getMultiple()>0) {
                foreach($form["propositions"]->getData() as $proposition)
                    $answer->addProposition($proposition);
            } else {
                $proposition = $this->getDoctrine()->getRepository(Proposition::class)->find($form['propositions']->getData());
                $answer->setPropositions($proposition);
            }
            foreach ($_POST as $cle=>$valeur) {
                if (strstr($cle,"category")) {
                    $assistive = $this->getDoctrine()->getRepository(Assistive::class)->find($valeur);
                    $answer->addAssistive($assistive);
                }
            }
            $manager->persist($answer);
            $manager->flush();
            return $this->redirectToRoute('survey_list');
        }
            return $this->render('survey/show.html.twig', [
            'controller_name' => 'SurveyController',
            'form' => $form->createView(),
            'survey' => $survey,
            'categories' => $categories,
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

    /**
     * @Route("/result/{id}", name="survey_result")
     */
    public function survey_result($id)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        return $this->render('survey/result.html.twig', [
            'controller_name' => 'SurveyController',
            'survey' => $survey,
        ]);
    }

    /**
    * @Route("/ajax", name="survey_ajax")
    */
   public function survey_ajax(Request $request)
   {
        $survey = $request->query->get('survey');   
        $device = $request->query->get('device');
        
        $propositions = $this->getDoctrine()->getRepository(Proposition::class)->findTestbis($survey);
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findSelectResult($survey,$device);
        $responseArray = array();
       foreach($answers as $answer) {
           $responseArray[] = array(
               "device_type" => $answer->getDeviceType(),
        );
        }
        return new JsonResponse($responseArray);
        
        
   }
}