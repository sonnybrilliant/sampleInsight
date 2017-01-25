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

class LoadRadioStation_03_UkhoziFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $ukhoziFM = new RadioStation();
        $ukhoziFM->setName("Ukhozi FM");
        $ukhoziFM->setBio("Ukhozi FM is one of the biggest radio stations on the planet and the largest in Africa with its listener-ship in constant access of 7.7 million over the past decade. Ukhozi FM broadcasts mainly in IsiZulu and loosely targets IsiZulu speaking and understanding audiences in South Africa. Ukhozi FM is a South African radio station, broadcasting nationwide and streaming to the world.

Ukhozi FM’s headquarters is in KwaZulu Natal, Durban, with its iconic personalities easily recognized throughout the country. The station caters to people ranging from young to elderly, specifically the youth, reinforcing a sense of pride and culture to the young people of South Africa.

The Station has great stature and sway that is unmatched and uses its platform to keep its listeners connected to their culture identity in a modern world context. With its influence exceeding that of many media player in SA, Ukhozi FM infuses its listeners with aspiration to be better, smarter and dream bigger.

Critically acclaimed for its useful content that is delivered with poise and deftness, affirming the cultural identity of its listeners, Ukhozi FM focuses on Edutainment and Infotainment as a guiding philosophy.

Ukhozi FM provides an interactive environment for its listeners, affording them access to News, Current Affairs, and Talk shows, Music, Drama, Sport, Education, Weather and Traffic, with much emphasis on local content.

Overall, the station’s precedence is to provide a foundation of upliftment, power, comfort, escapism, connectedness and culture to its listeners. Ukhozi FM continues to retain its number one spot as the country's most loved and listened radio station. Feel the spirit of Ukhozi FM. Luhamba Phambili!

");
        $ukhoziFM->setFrequency("90.8 - 93.4 FM");
        $ukhoziFM->setWebsite("http://www.ukhozifm.co.za/sabc/home/ukhozifm");
        $ukhoziFM->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-ukhozi/ukhozi_s.stream_64k");
        $ukhoziFM->setContactNumber('(031) 362-5402');
        $ukhoziFM->setContactEmail("info@ukhozifm.co.za");
        $ukhoziFM->setStatus($this->getReference('status-active'));
        $ukhoziFM->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $ukhoziFM->setLogo('ukhozi-icon.png');
        //languages
        $ukhoziFM->addLanguage($this->getReference('lang-zulu'));

        //Genres (Popular)
        $ukhoziFM->addGenre($this->getReference('genre-gospel'));
        $ukhoziFM->addGenre($this->getReference('genre-house'));
        $ukhoziFM->addGenre($this->getReference('genre-rnb'));
        $ukhoziFM->addGenre($this->getReference('genre-rap'));
        $ukhoziFM->addGenre($this->getReference('genre-kwaito'));
        $ukhoziFM->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $ukhoziFM->addProvince($this->getReference('province-gauteng'));
        $ukhoziFM->addProvince($this->getReference('province-limpopo'));
        $ukhoziFM->addProvince($this->getReference('province-free-state'));
        $ukhoziFM->addProvince($this->getReference('province-natal'));
        $ukhoziFM->addProvince($this->getReference('province-eastern-cape'));
        $ukhoziFM->addProvince($this->getReference('province-mpumalanga'));

        $ukhoziFM->setStreamId('8944');
        $ukhoziFM->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($ukhoziFM);
        $manager->flush();

        $this->setReference('radio_station-ukhozi',$ukhoziFM);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 8;
    }
}