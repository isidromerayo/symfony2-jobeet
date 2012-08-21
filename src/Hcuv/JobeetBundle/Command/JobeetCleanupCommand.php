<?php
namespace Hcuv\JobeetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Hcuv\JobeetBundle\Entity\Job;
/**
 * Created by JetBrains PhpStorm.
 * User: imerayo
 * Date: 8/21/12
 * Time: 11:00 AM
 * To change this template use File | Settings | File Templates.
 */
class JobeetCleanupCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this->setName('hcuv:jobeet:cleanup')
            ->setDescription('Cleanup Jobeet database')
            ->addArgument('days', InputArgument::OPTIONAL, 'The email', 90);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $days = $input->getArgument('days');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $nb = $em->getRepository('HcuvJobeetBundle:Job')->cleanup($days);
        $output->writeln(sprintf('Removed %d stale jobs', $nb));
    }
}

