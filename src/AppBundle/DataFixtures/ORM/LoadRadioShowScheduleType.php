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
use AppBundle\Entity\RadioShowScheduleType;

/**
 * Class LoadRadioShowScheduleType
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRadioShowScheduleType extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $weekdays = new RadioShowScheduleType("Weekday");
        $manager->persist($weekdays);

        $weekend = new RadioShowScheduleType("Weekend");
        $manager->persist($weekend);

        $everyday = new RadioShowScheduleType("Everyday");
        $manager->persist($everyday);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}