<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\RequestStatus;
use AppBundle\Entity\Url;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadRequestStatus
 */
class LoadRequestStatus implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $okStatus = new RequestStatus(200);
        $notFound = new RequestStatus(404);
        $error = new RequestStatus(500);

        $manager->persist($okStatus);
        $manager->persist($notFound);
        $manager->persist($error);

        $a = new Url('www.google.com.br', 1, $okStatus);

        $manager->persist($a);
        $manager->flush();
    }
}