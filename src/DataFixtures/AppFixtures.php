<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Survey;
use App\Entity\Proposition;
use App\Entity\TechnicalComponent;
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
        for ($i = 1; $i <= 5; $i++) {
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

        //initialisation des sondages
        for ($i = 1; $i <= 10; $i++) {
            $survey = new Survey();
            $survey->setTitle("Question n°$i");
            $survey->setQuestion($faker->text);
            $survey->setMultiple(true);
            $survey->setStatus("Initialisé");
            $survey->setClosingMessage($faker->text);
            $survey->updatedTimestamps();
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
                $technicalcomponent->setChoice(false);
                $technicalcomponent->setUrl($faker->url);
                $manager->persist($technicalcomponent);
            }
            $manager->persist($survey);
        }

        $manager->flush();
    }
}
