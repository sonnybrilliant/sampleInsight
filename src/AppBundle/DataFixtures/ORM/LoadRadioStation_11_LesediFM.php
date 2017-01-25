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

class LoadRadioStation_11_LesediFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Lesedi FM");
        $radio->setBio("Lesedi FM is a South African radio station, broadcasting semi-nationally and streaming to the world.

We are about entertaining, informing, educating and empowering South African Citizens in all Lesedi FM areas. We are a contemporary and a compelling radio brand in order to further more Sesotho speaking and Sesotho understanding people. ‘Re motlotlo ho e tsa phapang’ We are proud to make a difference.

Our communication is segment specific as to ascertain opportunities presented by DTT and the response by the various target markets i.e Youth, Gospel and Sesotho traditional and contemporary music.");
        $radio->setFrequency("88.7 - 90.6 FM");
        $radio->setWebsite("http://www.lesedifm.co.za/sabc/home/lesedifm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-lesedi/lesedi_s.stream_64k");
        $radio->setContactNumber('089 125 2016');
        $radio->setContactEmail("LesediFM@sabc.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('lesedi-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-south-sotho'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-free-state'));
        $radio->addProvince($this->getReference('province-natal'));
        $radio->addProvince($this->getReference('province-eastern-cape'));
        $radio->addProvince($this->getReference('province-western-cape'));
        $radio->addProvince($this->getReference('province-northern-cape'));
        $radio->addProvince($this->getReference('province-northern-west'));
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0011');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-lesedifm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 16;
    }
}