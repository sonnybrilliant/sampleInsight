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
use AppBundle\Entity\RadioStationType;

/**
 * Class LoadRadioStationType
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRadioStationType extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $radioStationTypeCommercial = new RadioStationType('commercial');
        $manager->persist($radioStationTypeCommercial);

        $radioStationTypePublicBroadcaster = new RadioStationType('public broadcasting');
        $manager->persist($radioStationTypePublicBroadcaster);

        $radioStationTypeCommunity = new RadioStationType('community');
        $manager->persist($radioStationTypeCommunity);

        $manager->flush();

        $this->addReference('radio-type-commercial', $radioStationTypeCommercial);
        $this->addReference('radio-type-public-broadcaster', $radioStationTypePublicBroadcaster);
        $this->addReference('radio-type-community', $radioStationTypeCommunity);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }


}