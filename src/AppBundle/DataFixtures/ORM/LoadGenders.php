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
use AppBundle\Entity\Gender;

/**
 * Class LoadGenders
 * @package AppBundle\DataFixtures\ORM
 *
 */
class LoadGenders extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $male = new Gender('male');
        $manager->persist($male);

        $female = new Gender('female');
        $manager->persist($female);

        $manager->flush();

        $this->addReference('gender-male', $male);
        $this->addReference('gender-female', $female);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }


}