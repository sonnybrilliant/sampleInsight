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

class LoadRadioStation_05_IkweziKweziFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Ikwekwezi FM");
        $radio->setBio("Based in Tshwane and broadcasting in isiNdebele, Ikwekwezi is a cultural service offering news, music, current affairs, talk shows, education, sports, weather and traffic. The average age of listeners is 33 and music styles include jazz, R&B, kwaito, house, gospel and African traditional music.");
        $radio->setFrequency("91.8 - 107.6 FM");
        $radio->setWebsite("http://www.ikwekwezifm.co.za/sabc/home/ikwekwezifm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-ikwekwezi/ikwekwezi_s.stream_64k");
        $radio->setContactNumber('(012) 431-5477');
        $radio->setContactEmail("info@ikwekwezifm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('ikwekwezi-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-ndebele'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-house'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-traditional'));
        $radio->addGenre($this->getReference('genre-jazz'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0005');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-ikwekwezi',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}