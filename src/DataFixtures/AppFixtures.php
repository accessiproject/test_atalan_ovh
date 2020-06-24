<?php

namespace App\DataFixtures;

//dependency injection
use Faker;
use App\Entity\User;
use App\Entity\Answer;
use App\Entity\Survey;
use App\Entity\Category;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
use App\Entity\Assistive;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    //constructor with one argument: the service UserPasswordEncoderInterface 
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    //method for loading test data
    public function load(ObjectManager $manager) {

        //initialization object Faker
        //configure the location, to have "French" data
        $faker = Faker\Factory::create('fr_FR');

        //creation users
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail("test$i@test.fr");
            $user->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($user, 'atalan');
            $user->setPassword($password);
            $user->updatedTimestamps();
            $manager->persist($user); //persistence an user object
            $manager->flush(); //save the assistives in the database 
        }


        //array of categories
        $array_categories = ["Lecteur d'écran", "Logiciel de grossissement", "Plage braille"];
        for ($i = 0; $i < count($array_categories); $i++) { //creation categories
            $category = new Category();
            $category->setType($array_categories[$i]);

            //initialization an array of assistives technology
            if ($array_categories[$i] == "Lecteur d'écran")
                $array_assistives = ["JAWS", "NVDA", "ORCA", "VoiceOver"]; //array screen readers
            else if ($array_categories[$i] == "Logiciel de grossissement")
                $array_assistives = ["ZoomText", "Loupe de Windows"]; //array magnification softwares
            else
                $array_assistives = ["Plage braille"]; //array braille display

            //creation assistives technology
            for ($j = 0; $j < count($array_assistives); $j++) {
                $assistive = new Assistive();
                $assistive->setName($array_assistives[$j]);
                $assistive->setCategory($category);
                $manager->persist($assistive); //persistence an assistive object
            }
            $manager->persist($category); //persistence a category object
            $manager->flush(); //save the categories and assistives in the database 
        }

        //creation surveys
        for ($i = 1; $i <= 2; $i++) {
            $survey = new Survey();
            $survey->setTitle("Question n°$i");
            $survey->setQuestion($faker->text);
            $survey->setInformation($faker->text);
            $survey->setMultiple(true);
            $survey->setShowAssistive(true);
            $survey->setNeedComponent(true);
            $survey->setStatus("Brouillon");
            $survey->setClosingMessage($faker->text);
            $survey->timeStamps();
            $survey->setClosedat($faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days', $timezone = null));

            //creation propositions
            for ($j = 1; $j <= 3; $j++) {
                $proposition = new Proposition();
                $proposition->setSurvey($survey);
                $proposition->setWording($faker->sentence($nbWords = 6, $variableNbWords = true));
                $manager->persist($proposition);
            }

            //creation TechnicalComponents
            for ($j = 1; $j <= 3; $j++) {
                $technicalcomponent = new TechnicalComponent();
                $technicalcomponent->setSurvey($survey);
                $technicalcomponent->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                $technicalcomponent->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true));
                $technicalcomponent->setChoice(false);
                $technicalcomponent->setUrl($faker->url);
                $manager->persist($technicalcomponent); //persistence technical components
            }
            $manager->persist($survey);
            $manager->flush();
        }
    }
}