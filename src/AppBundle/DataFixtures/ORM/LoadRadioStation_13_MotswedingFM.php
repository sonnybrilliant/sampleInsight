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

class LoadRadioStation_13_MotswedingFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Motsweding FM");
        $radio->setBio("A Setswana community service station, Motsweding - which means \"fountain\" - offers news, music, current affairs, talk shows, education, sport, weather and traffic, aimed at an African audience aged from 16 to 49. The music is contemporary: jazz, R&B, kwaito, house, gospel and African traditional. The SABC describes it as South Africa's \"only R&B vernacular radio station\".");
        $radio->setFrequency("97.9 - 91.0 FM");
        $radio->setWebsite("http://www.motswedingfm.co.za/sabc/home/motswedingfm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-motsweding/motsweding_s.stream_64k");
        $radio->setContactNumber('(018) 389 7111');
        $radio->setContactEmail("admin@motswedingfm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('motsweding-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-tswana'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-jazz'));
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-house'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-traditional'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-northern-cape'));
        $radio->addProvince($this->getReference('province-northern-west'));


        $radio->setStreamId('0013');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-motswedingfm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 18;
    }
}