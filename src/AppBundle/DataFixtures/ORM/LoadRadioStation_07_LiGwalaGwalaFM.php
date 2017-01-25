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

class LoadRadioStation_07_LiGwalaGwalaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Ligwalagwala FM");
        $radio->setBio("A Siswati cultural service, Ligwalagwala broadcasts news, music, current affairs, talk shows, education, sport, weather and traffic. The music includes jazz, R&B, kwaito, house, gospel and African traditional music. The station targets young, literate, aspirational and upwardly mobile black people living mainly in Mpumalanga area, with the primary target market being 25- to 49-year-olds.");
        $radio->setFrequency("92.5 - 103.8 FM");
        $radio->setWebsite("http://www.ligwalagwalafm.co.za/sabc/home/ligwalagwalafm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-ligwalagwala/ligwalagwala_s.stream_64k");
        $radio->setContactNumber('(013) 759 6611');
        $radio->setContactEmail("ligwalagwalafm@sabc.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('ligwala-gwala-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-swati'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-hip-hop'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-traditional'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-northern-west'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0007');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-ligwalagwala',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 12;
    }
}