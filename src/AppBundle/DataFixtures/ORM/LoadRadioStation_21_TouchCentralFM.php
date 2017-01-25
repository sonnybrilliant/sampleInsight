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

class LoadRadioStation_21_TouchCentralFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Touch Central FM");
        $radio->setBio("The voice of trailblazers, the pulse of urban culture and most certainly a voice that has no colour or gender, but lives in the soul of every person across Africa. A collaboration between two of South Africa’s leading radio and TV personalities – (Thabo Molefe) known as T-bo Touch, and Gareth Cliff – whose listenership and following remains unsurpassed to this day in the history of South African Broadcasting. Touch Central is here to give South Africa and the world HD Radio and Television with no censorship or any form of restriction.");
        $radio->setFrequency("Online");
        $radio->setWebsite("http://touchcentral.fm");
        $radio->setStream("http://edge.iono.fm/xice/touchcentral_live_high.mp3");
        $radio->setContactNumber("0861 555 189");
        $radio->setContactEmail("info@touchcentral.fm");
        $radio->setStatus($this->getReference('status-active'));
        $radio->setRadioStationType($this->getReference('radio-type-commercial'));
        $radio->setLogo('touchCentral-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-jazz'));
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-african'));
        $radio->addGenre($this->getReference('genre-afro-pop'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-internet'));

        $radio->setStreamId('9486');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-touchcentralfm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 25;
    }
}