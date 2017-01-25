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

class LoadRadioStation_15_Radio2000FM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Radio 2000 FM");
        $radio->setBio("To the listener, Radio 2000 is a laid back and non-intrusive radio station. Radio 2000, being a facility station, relies heavily on sports broadcasts. The result is that its listenership fluctuates, since it is often based on national and international sports events.");
        $radio->setFrequency("97.2 to 100.2 FM");
        $radio->setWebsite("http://www.radio2000.co.za/sabc/home/radio2000");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-radio2000/r2000_s.stream_64k");
        $radio->setContactNumber('(011) 714-4085');
        $radio->setContactEmail("info@radio2000.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-commercial'));
        $radio->setLogo('radio-2000-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-eastern-cape'));
        $radio->addProvince($this->getReference('province-western-cape'));
        $radio->addProvince($this->getReference('province-northern-west'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0015');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-radio2000fm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 19;
    }
}