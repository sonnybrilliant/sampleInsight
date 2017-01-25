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

class LoadRadioStation_02_5FM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $fiveFM = new RadioStation();
        $fiveFM->setName("5FM");
        $fiveFM->setBio("The SABC's trendy youth-oriented station, 5FM's emphasis is on the latest music, movies and South African youth trends. Broadcasting in English to South Africa's metropolitan areas, its music styles are international, and include a strong component of South African artists of world standard.");
        $fiveFM->setFrequency("98.0 FM");
        $fiveFM->setWebsite("www.5fm.co.za");
        $fiveFM->setStream("http://radio-int-edge-eu01-sc.antfarm.co.za:9034/5fm");
        $fiveFM->setContactNumber('089 11 00 505');
        $fiveFM->setContactEmail("info@5fm.co.za");
        $fiveFM->setStatus($this->getReference('status-active'));
        $fiveFM->setRadioStationType($this->getReference('radio-type-commercial'));
        $fiveFM->setLogo('5fm-icon.png');
        //languages
        $fiveFM->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $fiveFM->addGenre($this->getReference('genre-hip-hop'));
        $fiveFM->addGenre($this->getReference('genre-pop'));
        $fiveFM->addGenre($this->getReference('genre-rock'));
        $fiveFM->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $fiveFM->addProvince($this->getReference('province-gauteng'));
        $fiveFM->addProvince($this->getReference('province-limpopo'));
        $fiveFM->addProvince($this->getReference('province-free-state'));
        $fiveFM->addProvince($this->getReference('province-natal'));
        $fiveFM->addProvince($this->getReference('province-eastern-cape'));
        $fiveFM->addProvince($this->getReference('province-mpumalanga'));
        $fiveFM->addProvince($this->getReference('province-western-cape'));

        $fiveFM->setStreamId('8660');
        $fiveFM->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($fiveFM);
        $manager->flush();

        $this->setReference('radio_station-metro',$fiveFM);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 7;
    }
}