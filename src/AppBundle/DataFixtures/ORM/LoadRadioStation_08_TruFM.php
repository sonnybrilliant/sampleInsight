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

class LoadRadioStation_08_TruFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Tru FM");
        $radio->setBio("The station is unique in the SABC PBS stable as it is the only station primarily targeting the youth with two languages of broadcast, IsiXhosa and English. This differentiating factor matters as it provides the youth with a platform to express themselves, to engage with each other and interact with the rest of the world. This is done within the context of our mandate to inform, educate and entertain, to support and develop culture and as far as possible ensure the fair and equal treatment of the predominant languages in the province.
In essence, trufm is a platform of expression for young people without being judged.");
        $radio->setFrequency("89.9 - 104.1 FM");
        $radio->setWebsite("http://www.trufm.co.za/sabc/home/trufm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-trufm/trufm_sabc.stream_64k");
        $radio->setContactNumber('0860 352 212');
        $radio->setContactEmail("info@trufm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('tru-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-xhosa'));
        $radio->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-hip-hop'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-eastern-cape'));

        $radio->setStreamId('0008');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-trufm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 13;
    }
}