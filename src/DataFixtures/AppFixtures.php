<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Answer;
use App\Entity\Survey;
use App\Entity\Category;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
use App\Entity\AssistiveTechnology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        
        // initialisation de l'objet Faker
        // on peut préciser en paramètre la localisation, 
        // pour avoir des données qui semblent "françaises"
        $faker = Faker\Factory::create('fr_FR');

        //initialisation des utilisateurs
        for ($i = 1; $i <= 5; $i++)
        {
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail("kevin.bustamante$i@mail.novancia.fr");
            $user->setRoles(["ROLE_ADMIN"]);
            $password = $this->encoder->encodePassword($user, 'atalan');
            $user->setPassword($password);
            $user->updatedTimestamps();
            $manager->persist($user);
        }

        //initialisation des categories
        $type=["Synthèse vocale","Logiciel de grossissement","Plage braille"];
        for ($i = 0; $i < count($type); $i++)
        {
            $category = new Category();
            $category->setType($type[$i]);
            
            //initialisation des aides techniques
            if ($type[$i]=="Synthèse vocale")
                $tab=["JAWS","NVDA","ORCA","VoiceOver"];
            else if ($type[$i]=="Logiciel de grossissement")
                $tab=["ZoomText","Loupe de Windows"];
            else
                $tab=["Plage braille"];
            
            for ($j = 0; $j < count($tab); $j++)
            {    
                $assistiveTechnology = new AssistiveTechnology();
                $assistiveTechnology->setName($tab[$j]);
                $assistiveTechnology->setCategory($category);
                $manager->persist($assistiveTechnology);
            }
            $manager->persist($category);
            $manager->flush();
        }

        //initialisation des sondages
        for ($i = 1; $i <= 10; $i++)
        {
            $survey = new Survey();
            $survey->setTitle("Question n°$i");
            $survey->setQuestion($faker->text);
            $survey->setMultiple(true);
            $survey->setStatus("Initialisé");
            $survey->setClosingMessage($faker->text);
            $survey->updatedTimestamps();
            $survey->setClosedat($faker->dateTimeBetween($startDate = 'now', $endDate = '+30 days', $timezone = null));
            
            //creation propositions
            for ($j = 1; $j <= 3; $j++)
            {
                $proposition = new Proposition();
                $proposition->setSurvey($survey);
                $proposition->setWording($faker->sentence($nbWords = 6, $variableNbWords = true));
                $manager->persist($proposition);
            }
            
            //creation TechnicalComponents
            for ($j = 1; $j <= 3; $j++)
            {
                $technicalcomponent = new TechnicalComponent();
                $technicalcomponent->setSurvey($survey);
                $technicalcomponent->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                $technicalcomponent->setChoice(false);
                $technicalcomponent->setUrl($faker->url);
                $manager->persist($technicalcomponent);
            }

            //creation answers
            for ($j = 1; $j <= 10; $j++)
            {
                $answer = new Answer();
                $answer->setSurvey($survey);
                $answer->setUserAgent($faker->word);
            
                $tab_device=array("desktop","mobile");
                $index_device=array_rand($tab_device,1);
                $device=$tab_device[$index_device];
            
                if ($device=="desktop")
                {
                    //définir aléatoirement la configuration installée sur l'appareil
                    $tab_os=array("windows","linux","mac");
                    $index_os=array_rand($tab_os,1);
                    $os=$tab_os[$index_os];
                    //définir aléatoirement le navigateur utilisé
                    $tab_browser=array("firefox","chrome");
                    $index_browser=array_rand($tab_browser,1);
                    $browser=$tab_browser[$index_browser];
                } else {
                    //définir aléatoirement la configuration installée sur l'appareil
                    $tab_os=array("ios","android");
                    $index_os=array_rand($tab_os,1);
                    $os=$tab_os[$index_os];
                    //définir aléatoirement le navigateur utilisé
                    if ($os=="ios")
                    {
                        $browser="safari";
                    } else {
                        $tab_browser=array("firefox","chrome");
                        $index_browser=array_rand($tab_browser,1);
                        $browser=$tab_browser[$index_browser];
                    }
                }

                $answer->setDeviceType($device);
                $answer->setDeviceIdentifier("");
                $answer->setDeviceManufacturer("");
                $answer->setDeviceModel("");
                $answer->setOsName($os);
                $answer->setOsVersion("");
                $answer->setBrowserName($browser);
                $answer->setBrowserVersion("");
                $answer->setComment($faker->text);
                $answer->setEmail("answer$j@atalan.fr");
                $answer->setAccept(true);
                $answer->setAcceptedat($faker->dateTimeBetween($startDate = 'now', $endDate = '+5 days', $timezone = null));
                $answer->setCreatedat($faker->dateTimeBetween($startDate = 'now', $endDate = '+5 days', $timezone = null));
                $manager->persist($answer);
            }       
            $manager->persist($survey);
            $manager->flush();
        }
    }
}