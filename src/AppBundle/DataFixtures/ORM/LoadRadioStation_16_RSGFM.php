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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\RadioStation;

class LoadRadioStation_16_RSGFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{

    private $container;
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $radio = new RadioStation();
        $radio->setName("RSG FM");
        $radio->setBio("The SABC's national Afrikaans cultural service, Radio Sonder Grense - meaning \"radio without borders\" - targets the white, coloured and Indian market, offering news, current affairs, sport, lifestyle, education and music - alles in een - sonder grense. The format is primarily talk and current affairs, interspersed with soft pop and rock hits, 60% of which are English and 40% Afrikaans.");
        $radio->setFrequency("100 - 104 FM");
        $radio->setWebsite("http://www.rsg.co.za");
        $radio->setStream("rtmp://196.33.130.70:1935/rsg/RSG-Powered_by_Antfarm.stream");
        $radio->setContactNumber('(011) 714-2702');
        $radio->setContactEmail("info@rsg.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('rsg-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-afrikaans'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-gospel'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-eastern-cape'));
        $radio->addProvince($this->getReference('province-western-cape'));
        $radio->addProvince($this->getReference('province-northern-west'));
        $radio->addProvince($this->getReference('province-northern-cape'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0016');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-rsgfm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}