<?php

namespace App\Controller;

//dependencies injection
use App\Entity\Answer;
use App\Entity\Assistive;
use App\Entity\Category;
use App\Entity\Survey;
use App\Form\AnswerType;
use App\Form\SurveyType;
use App\Controller\AnswerController;
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
use App\Service\WhichBrowserService;


//The @IsGranted() annotation is the simplest way to restrict access on controller. Use it to restrict by roles,
/**
 * @IsGranted("ROLE_ADMIN")
 */
class SurveyController extends AbstractController
{

    //the survey_list() function displays the list of all surveys.
    //The survey_default") route is the home page.
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
    
    //the survey_edit() function is both to create (or modify) a survey.
    /**
     * @Route("/sondage/edition/{id}", name="survey_edit")
     */
    public function survey_edit($id, Request $request, EntityManagerInterface $manager)
    {

        if ($id > 0) {
            $survey = $manager->getRepository(Survey::class)->find($id);
        } else {
            $survey = new Survey();
            $survey->setStatus("Brouillon");
        }

        $proposition = new Proposition();
        $technicalComponent = new TechnicalComponent();
        $survey->getPropositions()->add($proposition);
        $survey->getTechnicalComponents()->add($technicalComponent);

        $form = $this->createForm(SurveyType::class, $survey, [
            'status' => $survey->getStatus(),
            'technicalcomponents' => $survey->getTechnicalComponents(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $proposition->setSurvey($survey);
            $technicalComponent->setSurvey($survey);
            $survey->timeStamps();

            // Enregistre le sondage en base
            $manager->persist($survey);
            $manager->flush();

            return $this->redirectToRoute('survey_show', ['id' => $survey->getId()]);
        }
        return $this->render('survey/edit.html.twig', [
            'controller_name' => 'SurveyController',
            'form' => $form->createView(),
            'id' => $survey->getId() ? $survey->getId() : 0
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
    public function survey_result($id)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        $number_answers = $this->getDoctrine()->getRepository(Answer::class)->countResults($id);
        return $this->render('survey/result.html.twig', [
            'controller_name' => 'SurveyController',
            'survey' => $survey,
            'number_answers' => $number_answers,
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
        $responseArray = array();
        foreach ($array_parameters as $key => $parameter) {
            if ($key == "proposition_id") {
                $propostions = $this->getDoctrine()->getRepository(Proposition::class)->findSelectProposition($parameter_survey);
                $responseArray[$key] = $propostions;
            } elseif ($key == "assistive_id") {
                $assistives = $this->getDoctrine()->getRepository(Assistive::class)->findSelectAssistive($parameter_survey);
                $responseArray[$key] = $assistives;
            } else {
                $parameter_request = "a." . $parameter;
                $parameter = $this->getDoctrine()->getRepository(Answer::class)->findSelectResult($parameter_survey, $parameter_request);
                $responseArray[$key] = $parameter;
            }
        }
        $resultat = json_encode($responseArray);
        return new response($resultat);
    }

    /**
     * @Route("/ajaxtable", name="survey_ajaxtable")
     */
    public function survey_ajaxtable(Request $request)
    {
        $array_parameters = $request->query->all();
        $request_survey = $array_parameters["survey"];
        $request_proposition_id = $array_parameters["proposition_id"];
        if ($request_proposition_id == "id")
            $request_proposition_id = "p.id";

        $request_assistive_id = $array_parameters["assistive_id"];
        if ($request_assistive_id == "id")
            $request_assistive_id = "s.id";

        unset($array_parameters["survey"]);
        unset($array_parameters["proposition_id"]);
        unset($array_parameters["assistive_id"]);
        foreach ($array_parameters as $key => $parameter) {
            if (preg_match("/$parameter/", $key)) {
                $parameter = "a." . $parameter;
                $array_parameters[$key] = $parameter;
            } else {
                $parameter = "'" . $parameter . "'";
                $array_parameters[$key] = $parameter;
            }
        }
        $results = $this->getDoctrine()->getRepository(Answer::class)->findSelectResults($request_survey, $array_parameters["answer_id"], $array_parameters["answer_device_type"], $array_parameters["answer_os_name"], $array_parameters['answer_browser_name'], $request_proposition_id, $request_assistive_id);
        $responseArray = array();
        for ($i = 0; $i < count($results); $i++) {
            $responseArray[$i]["id"] = "Answer n°" . $results[$i]->getId();
            $responseArray[$i]["comment"] = $results[$i]->getComment();
            $responseArray[$i]["email"] = $results[$i]->getEmail();
            foreach ($results[$i]->getPropositions() as $proposition)
                $responseArray[$i]["proposition_wording"][] = $proposition->getWording();
            $responseArray[$i]["device_type"] = $results[$i]->getDeviceType() . " " . $results[$i]->getDeviceIdentifier() . " " . $results[$i]->getDeviceManufacturer() . " " . $results[$i]->getDeviceModel();
            $responseArray[$i]["os_name"] = $results[$i]->getOsName() . " " . $results[$i]->getOsVersion();
            $responseArray[$i]["browser_name"] = $results[$i]->getBrowserName() . " " . $results[$i]->getBrowserVersion();
            foreach ($results[$i]->getAssistives() as $assistive)
                $responseArray[$i]["assistive_name"][] = $assistive->getName();
            $responseArray[$i]["user_agent"] = $results[$i]->getUserAgent();
        }
        $json = json_encode($responseArray);
        return new response($json);
    }
}