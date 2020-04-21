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
    public function survey_result($id, Request $request)
    {
        $survey = $this->getDoctrine()->getRepository(Survey::class)->find($id);
        $array_parameters = ["id"=>"id","device_type"=>"device_type","os_name"=>"os_name","browser_name"=>"browser_name"];
        $responseArray=array();
        foreach ($array_parameters as $key=>$parameter) {
            $parameter_request = "a." . $parameter;
            $parameter = $this->getDoctrine()->getRepository(Answer::class)->findSelectResult(6, $parameter_request);    
            $responseArray[$key]=$parameter;
        }
        //var_dump($responseArray);
        $resultat=json_encode($responseArray);
        echo $resultat;
        return $this->render('survey/result.html.twig', [
            'controller_name' => 'SurveyController',
            'survey' => $survey,
            'resultat' => $resultat,
            'responseArray' => $responseArray,
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
            $parameter_request = "a." . $parameter;
            $parameter = $this->getDoctrine()->getRepository(Answer::class)->findSelectResult($parameter_survey, $parameter_request);    
            $responseArray[$key]=$parameter;
        }
        $resultat=json_encode($responseArray);
        return new response($resultat);
        //dd($x);
        //return new JsonResponse($responseArray);
        //$jsonResult = json_encode($responseArray);
        //return $jsonResult;
    }
}
