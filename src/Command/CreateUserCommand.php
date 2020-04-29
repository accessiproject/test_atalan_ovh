<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    private $em;

    private $passwordEncoder;

    private $validator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('firstname', InputArgument::REQUIRED, 'The firstname'),
                new InputArgument('lastname', InputArgument::REQUIRED, 'The lastname'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ))
            ;
    }

    /**
     *      * {@inheritdoc}
     *           */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();

        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $password = $this->passwordEncoder->encodePassword($user, $password);

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPassword($password);
        $user->updatedTimestamps();

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln(sprintf('Created user %s', $firstname));
    }

    /**
     *      * {@inheritdoc}
     *           */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('firstname')) {
            $question = new Question('Please choose a firstname:');
            $questions['firstname'] = $question;
        }

        if (!$input->getArgument('lastname')) {
            $question = new Question('Please choose a lastname:');
            $questions['lastname'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
