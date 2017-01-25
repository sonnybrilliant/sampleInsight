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

class LoadRadioStation_18_VeritasFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Radio Veritas");
        $radio->setBio("In a world that cries out for GOOD news, Radio Veritas, the only Catholic radio station in South Africa, serves the needs of Christians across the board!");
        $radio->setFrequency("92.7 FM");
        $radio->setWebsite("http://www.radioveritas.co.za/");
        $radio->setStream("http://188.138.56.4:8030");
        $radio->setContactNumber('+27 (0)11 663-4700');
        $radio->setContactEmail("info@radioveritas.co.za");
        $radio->setStatus($this->getReference('status-active'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('veritas-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));
        $radio->addLanguage($this->getReference('lang-south-sotho'));
        $radio->addLanguage($this->getReference('lang-tswana'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-african'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));

        $radio->setStreamId('9355');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-veritasfm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 22;
    }
}