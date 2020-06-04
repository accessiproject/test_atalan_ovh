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
use App\Repository\AssistiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/sondage/edition/{id}", name="survey_edit")
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
            $survey->timeStamps();

            // Enregistre le sondage en base
            $manager->persist($survey);
            $manager->flush();

            return $this->redirectToRoute('survey_show', ['id' => $survey->getId() ]);
        }
        return $this->render('survey/edit.html.twig', [
            'controller_name' => 'SurveyController',
            'form' => $form->createView(),
            'id' => $survey->getId() ? $survey->getId() : 0
        ]);
    }

    /**
     * @Route("/sondage/consultation/{id}", name="survey_show")
     */
    public function survey_show($id, Request $request, EntityManagerInterface $manager)
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
     * @Route("/sondage/suppression/{id}", name="survey_delete")
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
     * @Route("/sondage/resultat/{id}", name="survey_result")
     */
    public function survey_result($id, Request $request)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        /*
        $array_parameters=[
            "survey"=>6,
            "answer_id"=>"id",
            "proposition_id"=>"id",
            "answer_device_type"=>"desktop",
            "answer_os_name"=>"Windows",
            "answer_browser_name"=>"browser_name",
            "assistive_id"=>"id"
        ];
        $request_survey = $array_parameters["survey"];
        unset($array_parameters["survey"]);
        unset($array_parameters["proposition_id"]);
        unset($array_parameters["assistive_id"]);
        foreach ($array_parameters as $key=>$parameter) {
            if (preg_match("/$parameter/",$key)) {
                $parameter = "a." . $parameter; 
                $array_parameters[$key]=$parameter;
            } else {
                $parameter = "'" . $parameter . "'"; 
                $array_parameters[$key]=$parameter;
            }
        }
        //var_dump($array_parameters);
        $results = $this->getDoctrine()->getRepository(Answer::class)->findSelectResults($request_survey,$array_parameters["answer_id"],$array_parameters["answer_device_type"],$array_parameters["answer_os_name"],$array_parameters['answer_browser_name']);
        $responseArray=array();
        for ($i=0;$i<count($results);$i++) {
            $responseArray[$i]["id"]=$results[$i]->getId();
            $responseArray[$i]["comment"]=$results[$i]->getComment();
            $responseArray[$i]["email"]=$results[$i]->getEmail();
            $responseArray[$i]["user_agent"]=$results[$i]->getUserAgent();
            $responseArray[$i]["device_type"]=$results[$i]->getDeviceType();
            $responseArray[$i]["device_identifier"]=$results[$i]->getDeviceIdentifier();
            $responseArray[$i]["device_manufacturer"]=$results[$i]->getDeviceManufacturer();
            $responseArray[$i]["device_model"]=$results[$i]->getDeviceModel();
            $responseArray[$i]["os_name"]=$results[$i]->getOsName();
            $responseArray[$i]["os_version"]=$results[$i]->getOsVersion();
            $responseArray[$i]["browser_name"]=$results[$i]->getBrowserName();
            $responseArray[$i]["browser_version"]=$results[$i]->getBrowserVersion();
            foreach ($results[$i]->getPropositions() as $proposition)
                $responseArray[$i]["proposition_wording"][]=$proposition->getWording();
            foreach ($results[$i]->getAssistives() as $assistive)
                $responseArray[$i]["assistive_name"][]=$assistive->getName();
        }
        $json=array();
        $json=json_encode($responseArray);
        */
        //echo $json;
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
        $array_parameters = $request->query->all();
        $parameter_survey = $array_parameters["survey"];
        unset($array_parameters["survey"]);
        $responseArray=array();
        foreach ($array_parameters as $key=>$parameter) {
            if ($key=="proposition_id") {
                $propostions = $this->getDoctrine()->getRepository(Proposition::class)->findSelectProposition($parameter_survey);
                $responseArray[$key]=$propostions;
            } elseif ($key=="assistive_id") {
                $assistives = $this->getDoctrine()->getRepository(Assistive::class)->findSelectAssistive($parameter_survey);
                $responseArray[$key]=$assistives;
            } else {
                $parameter_request = "a." . $parameter;
                $parameter = $this->getDoctrine()->getRepository(Answer::class)->findSelectResult($parameter_survey, $parameter_request);    
                $responseArray[$key]=$parameter;
            }
        }
        $resultat=json_encode($responseArray);
        return new response($resultat);
        //dd($x);
        //return new JsonResponse($responseArray);
        //$jsonResult = json_encode($responseArray);
        //return $jsonResult;
    }

    /**
     * @Route("/ajaxtable", name="survey_ajaxtable")
     */
    public function survey_ajaxtable(Request $request)
    {
        $array_parameters = $request->query->all();
        
        $request_survey = $array_parameters["survey"];
        $request_proposition_id = $array_parameters["proposition_id"];
        if ($request_proposition_id=="id")
            $request_proposition_id = "p.id";

        $request_assistive_id = $array_parameters["assistive_id"];
        if ($request_assistive_id=="id")
            $request_assistive_id = "s.id";
            
        unset($array_parameters["survey"]);
        unset($array_parameters["proposition_id"]);
        unset($array_parameters["assistive_id"]);
        foreach ($array_parameters as $key=>$parameter) {
            if (preg_match("/$parameter/",$key)) {
                $parameter = "a." . $parameter; 
                $array_parameters[$key]=$parameter;
            } else {
                $parameter = "'" . $parameter . "'"; 
                $array_parameters[$key]=$parameter;
            }
        }
        $results = $this->getDoctrine()->getRepository(Answer::class)->findSelectResults($request_survey,$array_parameters["answer_id"],$array_parameters["answer_device_type"],$array_parameters["answer_os_name"],$array_parameters['answer_browser_name'],$request_proposition_id,$request_assistive_id);
        $responseArray=array();
        for ($i=0;$i<count($results);$i++) {
            $responseArray[$i]["id"]=$results[$i]->getId();
            $responseArray[$i]["comment"]=$results[$i]->getComment();
            $responseArray[$i]["email"]=$results[$i]->getEmail();
            $responseArray[$i]["user_agent"]=$results[$i]->getUserAgent();
            $responseArray[$i]["device_type"]=$results[$i]->getDeviceType();
            $responseArray[$i]["device_identifier"]=$results[$i]->getDeviceIdentifier();
            $responseArray[$i]["device_manufacturer"]=$results[$i]->getDeviceManufacturer();
            $responseArray[$i]["device_model"]=$results[$i]->getDeviceModel();
            $responseArray[$i]["os_name"]=$results[$i]->getOsName();
            $responseArray[$i]["os_version"]=$results[$i]->getOsVersion();
            $responseArray[$i]["browser_name"]=$results[$i]->getBrowserName();
            $responseArray[$i]["browser_version"]=$results[$i]->getBrowserVersion();
            foreach ($results[$i]->getPropositions() as $proposition)
                $responseArray[$i]["proposition_wording"][]=$proposition->getWording();
            foreach ($results[$i]->getAssistives() as $assistive)
                $responseArray[$i]["assistive_name"][]=$assistive->getName();
        }
        //$json=array();
        $json=json_encode($responseArray);
        return new response($json);
    }

    /**
     * @Route("/composant//{id}", name="survey_technicalcomponent")
     */
    public function answer_technicalcomponent($id)
    {
        $technicalComponent = $this->getDoctrine()->getRepository(TechnicalComponent::class)->find($id);
        return $this->render('survey/technicalcomponent.html.twig', [
            'controller_name' => 'SurveyController',
            'technicalComponent' => $technicalComponent,
        ]);
    }
}
