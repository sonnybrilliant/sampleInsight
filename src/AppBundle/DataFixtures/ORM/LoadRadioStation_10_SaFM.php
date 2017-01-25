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

class LoadRadioStation_10_SaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Sa FM");
        $radio->setBio("\"The station for the well-informed,\" SAfm covers the news and canvasses the opinions of the country. John Perlman's popular After Eight Debate brings some of the most prominent South Africans into the studio to discuss the events that are in the news. In accordance with its mandate as an SABC public broadcasting service in English, SAfm also explores broader themes and subjects relevant to its target market and delivers the information in a manner to benefit all South Africans.");
        $radio->setFrequency("104 - 107 FM");
        $radio->setWebsite("http://www.safm.co.za/sabc/home/safm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-phalaphala/phalaphala_s.stream_64k");
        $radio->setContactNumber('(011) 714- 4442');
        $radio->setContactEmail("info@safm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('sa-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));
        
        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-traditional'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-eastern-cape'));
        $radio->addProvince($this->getReference('province-western-cape'));
        $radio->addProvince($this->getReference('province-northern-cape'));
        $radio->addProvince($this->getReference('province-northern-west'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0010');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-safm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 15;
    }
}