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
use AppBundle\Entity\ContentType;

/**
 * Class LoadContentType
 * @package AppBundle\DataFixtures\ORM
 */
class LoadContentType extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeMusic = new ContentType('music');
        $manager->persist($typeMusic);

        $typeSlogan = new ContentType('slogan');
        $manager->persist($typeSlogan);

        $typePromo = new ContentType('promo');
        $manager->persist($typePromo);

        $typeAdvert = new ContentType('advertisement');
        $manager->persist($typeAdvert);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }


}