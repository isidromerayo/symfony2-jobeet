<?php
namespace Hcuv\JobeetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Hcuv\JobeetBundle\Entity\User;
/**
 *
 * User: imerayo
 */
class JobeetUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('hcuv:jobeet:users')
            ->setDescription('Add Jobeet users')
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
            ->addArgument('password', InputArgument::REQUIRED, 'The password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $user = new User();
        $user->setUsername($username);
        // encode password
        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encoderPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encoderPassword);
        $em->persist($user);
        $em->flush();

        $output->writeln(
            sprintf('Added %s user with password %s', $username, $password));
    }
}
