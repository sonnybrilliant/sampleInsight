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

class LoadRadioStation_06_LotusFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Lotus FM");
        $radio->setBio("Lotus FM is a metropolitan, music- and talk-focused Indian lifestyle station, reflecting the music, culture and lifestyle of the progressive South African Indian community. With the pay-off line \"Not everything is black and white\", the station emphasises both the South African and Indian identity of its listeners.");
        $radio->setFrequency("92.5 - 103.8 FM");
        $radio->setWebsite("hhttp://www.lotusfm.co.za/sabc/home/lotusfm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-lotusfm/lotus_s.stream_64k");
        $radio->setContactNumber('(031) 362 5444');
        $radio->setContactEmail("marketing@lotusfm.co.za ");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('lotus-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-traditional'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-western-cape'));

        $radio->setStreamId('0006');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-lotus',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 11;
    }
}