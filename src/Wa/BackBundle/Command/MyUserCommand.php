<?php
namespace Wa\BackBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wa\BackBundle\Entity\User;

class MyUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mycommande:createUser')
            ->setDescription('permet de créer un user')
            //Obliger d'entrer ujn login et mdp
            ->addArgument('login', InputArgument::REQUIRED, 'veuillez entrer votre login')
            ->addArgument("mdp", InputArgument::REQUIRED, 'veuillez entrer votre mot de passe');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération de doctrine
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        $user->setFirstname("Doudou");
        $user->setLastname("Le Banner");
        $user->setEmail("doudou@gmail.com");
        //Récupérer les arguments taper dans la console
        $user->setLogin($input->getArgument('login'));
        $user->setPassword($input->getArgument('mdp'));
        $user->setGender(0);
        $user->setAddress("OKLM");
        $user->setPhone("0606060606");

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>Vôtre utilisateur a bien été créé</info>');
    }

}
