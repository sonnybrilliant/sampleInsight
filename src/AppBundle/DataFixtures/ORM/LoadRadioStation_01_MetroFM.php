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

class LoadRadioStation_01_MetroFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Metro FM");
        $radio->setBio("Broadcast in English, Metro FM is the largest national commercial station in South Africa, targeting 25- to 34-year-old black urban adults - who its owner the SABC describes as 'trendy, innovative, progressive and aspirational'. While the station does have some information and educational aspects, the focus is firmly on contemporary international music - hip-hop, R&B, kwaito and more.");
        $radio->setFrequency("96.4 FM");
        $radio->setWebsite("www.metrofm.co.za");
        $radio->setStream("rtmp://cdn-radio-za-metro-sabc-metrofm.antfarm.co.za:80/metro/metro_s.stream_64k");
        $radio->setContactNumber('089 110 3377');
        $radio->setContactEmail("info@metrofm.co.za");
        $radio->setStatus($this->getReference('status-active'));
        $radio->setRadioStationType($this->getReference('radio-type-commercial'));
        $radio->setLogo('metrofm-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-hip-hop'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-rap'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-eastern-cape'));
        $radio->addProvince($this->getReference('province-western-cape'));

        $radio->setStreamId('8661');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));

        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-metro',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 6;
    }
}