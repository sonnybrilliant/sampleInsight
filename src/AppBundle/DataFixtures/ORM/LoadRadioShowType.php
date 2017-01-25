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
use AppBundle\Entity\RadioShowType;

/**
 * Class LoadRadioShowType
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRadioShowType extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $typeMorningTalk = new RadioShowType("Morning talk");
        $manager->persist($typeMorningTalk);
        $this->addReference('show-type-morning-talk', $typeMorningTalk);

        $typeSportTalk = new RadioShowType("Sports talk");
        $manager->persist($typeSportTalk );
        $this->addReference('show-type-sport-talk', $typeSportTalk);

        $typeLateNightTalk= new RadioShowType("Late night talk");
        $manager->persist($typeLateNightTalk);
        $this->addReference('show-type-late-night-talk', $typeLateNightTalk);

        $typeMiddayTalk = new RadioShowType("Midday talk");
        $manager->persist($typeMiddayTalk);
        $this->addReference('show-type-midday-talk', $typeMiddayTalk);

        $typeBusinessTalk = new RadioShowType("Business talk");
        $manager->persist($typeBusinessTalk);
        $this->addReference('show-type-business-talk', $typeBusinessTalk);

        $typePoliticalTalk = new RadioShowType("Political talk");
        $manager->persist($typePoliticalTalk);
        $this->addReference('show-type-political-talk', $typePoliticalTalk);

        $typeReligious = new RadioShowType("Religious");
        $manager->persist($typeReligious);
        $this->addReference('show-type-religious', $typeReligious);

        $typeChildren = new RadioShowType("Children");
        $manager->persist($typeChildren);
        $this->addReference('show-type-children', $typeChildren);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}