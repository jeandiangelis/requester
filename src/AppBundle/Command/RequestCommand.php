<?php

namespace AppBundle\Command;

use AppBundle\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RequestCommand
 */
class RequestCommand extends ContainerAwareCommand
{
    const URL_ID = 'id';

    protected function configure()
    {
        $this
            ->setName('launch:request')
            ->setDescription('Request launcher')
            ->addArgument(
                static::URL_ID,
                InputArgument::REQUIRED
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument(static::URL_ID);
        
        $doctrine = $this
            ->getContainer()
            ->get('doctrine')
        ;

        $em = $doctrine->getEntityManager();
        $repo = $doctrine->getRepository(Url::class);
        $url = $repo->find($id);

        $url->setLaunched(true);

        $em->persist($url);
        $em->flush();
    }
}