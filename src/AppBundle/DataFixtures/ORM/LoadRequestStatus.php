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
        $loading = new RequestStatus(-1);

        $manager->persist($okStatus);
        $manager->persist($notFound);
        $manager->persist($error);
        $manager->persist($loading);

        $manager->flush();
    }
}