<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Status;

/**
 * Class LoadStatus
 * @package AppBundle\DataFixtures\ORM
 */
class LoadStatus extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $active = new Status('active','active');
        $manager->persist($active);
        $this->addReference('status-active', $active);

        $suspended = new Status('suspended','suspended');
        $manager->persist($suspended);
        $this->addReference('status-suspended',$suspended);

        $expired = new Status('expired','expired');
        $manager->persist($expired);
        $this->addReference('status-expired',$expired);

        $streaming = new Status('streaming','streaming');
        $manager->persist($streaming);
        $this->addReference('status-streaming',$streaming);

        $paused = new Status('paused','paused');
        $manager->persist($paused);
        $this->addReference('status-paused',$paused);

        $rejected = new Status('rejected','rejected');
        $manager->persist($rejected);
        $this->addReference('status-rejected',$rejected);

        $pending = new Status('pending','pending');
        $manager->persist($pending);

        $ready = new Status('ready','ready');
        $manager->persist($ready);

        $queued = new Status('queued','queued');
        $manager->persist($queued);

        $playlisted = new Status('playlisted','playlisted');
        $manager->persist($playlisted);

        $cancelled = new Status('cancelled','cancelled');
        $manager->persist($cancelled);

        $error = new Status('error','error');
        $manager->persist($error);

        $notVerified = new Status('not verified','notverified');
        $manager->persist($notVerified);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}