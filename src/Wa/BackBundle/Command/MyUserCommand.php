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

        //Récupérer les log et mdp
        $Login = $input->getArgument('login');
        $Mdp = $input->getArgument('mdp');

        $user = new User();

        //On récupère tout ce quil y a daans l'encoder au niveau du fichier yml
        $factory = $this->getContainer()->get('security.encoder_factory');
        // Je récupère l'encoder de la class Troiswa\BackBundle\Entity\User
        $encoder = $factory->getEncoder($user);
        //On encode le mdp
        $newPassword = $encoder->encodePassword($Mdp, $user->getSalt());

        $user->setFirstname("Doudou");
        $user->setLastname("Le Banner");
        $user->setEmail("lulu@gmail.com");
        //Récupérer les arguments taper dans la console
        $user->setLogin($Login);
        $user->setPassword($newPassword);
        $user->setGender(rand(0,1));
        $user->setAddress("OKLM");
        $user->setPhone("0606060606");

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>Vôtre utilisateur a bien été créé</info>');
    }

}
