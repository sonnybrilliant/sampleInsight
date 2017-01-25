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

class LoadRadioStation_17_ChannelAfricaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Channel Africa");
        $radio->setBio("The international radio service of the SABC offers a multilingual source of information on Africa - with news, music and sports. Broadcasts are in Chinyanja, Silozi, Kiswahili, English, French and Portuguese, with shortwave broadcasts covering south, east, central and west Africa, satellite broadcasts covering the sub-Saharan region - and internet broadcasts covering the entire world.");
        $radio->setFrequency("Satelite");
        $radio->setWebsite("http://www.channelafrica.co.za/sabc/home/channelafrica");
        $radio->setStream("rtmp://196.33.130.77/channelafrica247/channelafrica247.stream");
        $radio->setContactNumber('+27 (11) 714-4832');
        $radio->setContactEmail("info@channelafrica.org");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-commercial'));
        $radio->setLogo('channel-afrika-icon-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));
        $radio->addLanguage($this->getReference('lang-chinyanja'));
        $radio->addLanguage($this->getReference('lang-silozi'));
        $radio->addLanguage($this->getReference('lang-portuguese'));
        $radio->addLanguage($this->getReference('lang-kiswahili'));
        $radio->addLanguage($this->getReference('lang-french'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-african'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-global'));

        $radio->setStreamId('0017');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-channelafricafm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 21;
    }
}