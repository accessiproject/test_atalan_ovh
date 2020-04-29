<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SetRoleUserCommand extends Command
{
    private $em;

    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:set-role-user')
            ->setDescription('Set the user\'s role.')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('role', InputArgument::REQUIRED, 'New role'),
            ))
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');

        $user = $this->em->getRepository(User::class)->findOneByEmail($email);

        $role = $input->getArgument('role');

        $roles = $user->setRoles([$role]);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->flush();

        $output->writeln(sprintf('New role defined for %s', $email));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('email')) {
            $question = new Question('The email of the existing user:');
            $questions['email'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new Question('New role:');
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
