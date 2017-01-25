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

class LoadRadioStation_04_UMhloboWeneneFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $mhlobo = new RadioStation();
        $mhlobo->setName("Umhlobo Wenene FM");
        $mhlobo->setBio("The SABC's isiXhosa cultural service and the second-largest radio station in South Africa, Umhlobo Wenene means \"a true friend\". The format includes news, current affairs, talk shows, education, sport, weather and traffic, with jazz, R&B, kwaito, house, gospel and African traditional music.");
        $mhlobo->setFrequency("90.7 FM");
        $mhlobo->setWebsite("http://www.umhlobowenenefm.co.za/sabc/home/umhlobowenenefm/");
        $mhlobo->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-umhlobowenene/umhlobowenene_s.stream_64k");
        $mhlobo->setContactNumber('(041) 391-1911');
        $mhlobo->setContactEmail("info@umhlobowenenefm.co.za");
        $mhlobo->setStatus($this->getReference('status-active'));
        $mhlobo->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $mhlobo->setLogo('mhlobo-wenene-icon.png');
        //languages
        $mhlobo->addLanguage($this->getReference('lang-xhosa'));

        //Genres (Popular)
        $mhlobo->addGenre($this->getReference('genre-gospel'));
        $mhlobo->addGenre($this->getReference('genre-house'));
        $mhlobo->addGenre($this->getReference('genre-rnb'));
        $mhlobo->addGenre($this->getReference('genre-traditional'));
        $mhlobo->addGenre($this->getReference('genre-jazz'));
        $mhlobo->addGenre($this->getReference('genre-kwaito'));
        $mhlobo->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $mhlobo->addProvince($this->getReference('province-gauteng'));
        $mhlobo->addProvince($this->getReference('province-free-state'));
        $mhlobo->addProvince($this->getReference('province-natal'));
        $mhlobo->addProvince($this->getReference('province-eastern-cape'));
        $mhlobo->addProvince($this->getReference('province-western-cape'));
        $mhlobo->addProvince($this->getReference('province-northern-cape'));
        $mhlobo->addProvince($this->getReference('province-northern-west'));


        $mhlobo->setStreamId('8945');
        $mhlobo->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($mhlobo);
        $manager->flush();

        $this->setReference('radio_station-mhlobo',$mhlobo);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 9;
    }
}