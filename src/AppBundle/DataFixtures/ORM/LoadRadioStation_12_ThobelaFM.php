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

class LoadRadioStation_12_ThobelaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Thobela FM");
        $radio->setBio("Thobela FM is a proud-modern-home of N. Sotho speaking and understanding people, representing cultural diversity by preserving rich heritage through information, education and entertainment for current and future generations.");
        $radio->setFrequency("98.7 - 91.9 FM");
        $radio->setWebsite("http://www.thobelafm.co.za/sabc/home/thobelafm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-thobela/thobela_s.stream_64k");
        $radio->setContactNumber('(015) 297 8941');
        $radio->setContactEmail("info@thobelafm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('thobela-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-north-sotho'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-traditional'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0012');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-thobelafm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 17;
    }
}