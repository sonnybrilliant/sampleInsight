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

class LoadRadioStation_09_PhalaPhalaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Phalaphala FM");
        $radio->setBio("Phalaphala FM is a contemporary radio station targeting 16- to 34-year-old aspirational and upwardly mobile Tshivenda-speaking people in Limpopo and Gauteng, an audience the SABC describes as \"future decision-makers\".");
        $radio->setFrequency("97.9 - 108.1 FM");
        $radio->setWebsite("http://www.phalaphalafm.co.za/sabc/home/phalaphalafm");
        $radio->setStream("rtmp://cdn-radio-za-colony-sabc.antfarm.co.za:80/sabc-phalaphala/phalaphala_s.stream_64k");
        $radio->setContactNumber('(015)290 0260');
        $radio->setContactEmail("ravelens@sabc.co.za ");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-public-broadcaster'));
        $radio->setLogo('phalaphala-icon-fm.png');
        //languages
        $radio->addLanguage($this->getReference('lang-venda'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-traditional'));
        $radio->addGenre($this->getReference('genre-afro-pop'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));
        $radio->addProvince($this->getReference('province-limpopo'));

        $radio->setStreamId('0009');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-phalaphalafm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 14;
    }
}