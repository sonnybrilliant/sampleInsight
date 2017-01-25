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
use AppBundle\Entity\Province;

/**
 * Class LoadProvinces
 * @package AppBundle\DataFixtures\ORM
 */
class LoadProvinces extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $easternCape = new Province('The Eastern Cape');
        $manager->persist($easternCape);

        $freeState = new Province('The Free State');
        $manager->persist($freeState);

        $gauteng = new Province('Gauteng');
        $manager->persist($gauteng);

        $natal = new Province('KwaZulu-Natal');
        $manager->persist($natal);

        $limpopo = new Province('Limpopo');
        $manager->persist($limpopo);

        $mpumalanga = new Province('Mpumalanga');
        $manager->persist($mpumalanga);

        $northernCape = new Province('The Northern Cape');
        $manager->persist($northernCape);

        $northWest = new Province('North West');
        $manager->persist($northWest);

        $westernCape = new Province('The Western Cape');
        $manager->persist($westernCape);

        $national = new Province('National');
        $manager->persist($national);

        $global = new Province('Global');
        $manager->persist($global);

        $internet = new Province('Internet');
        $manager->persist($internet);



        $manager->flush();

        $this->addReference('province-eastern-cape', $easternCape);
        $this->addReference('province-free-state', $freeState);
        $this->addReference('province-gauteng', $gauteng);
        $this->addReference('province-natal', $natal);
        $this->addReference('province-limpopo', $limpopo);
        $this->addReference('province-mpumalanga', $mpumalanga);
        $this->addReference('province-northern-cape', $northernCape);
        $this->addReference('province-northern-west', $northWest);
        $this->addReference('province-western-cape', $westernCape);
        $this->addReference('province-national', $national);
        $this->addReference('province-global', $global);
        $this->addReference('province-internet', $internet);

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

}