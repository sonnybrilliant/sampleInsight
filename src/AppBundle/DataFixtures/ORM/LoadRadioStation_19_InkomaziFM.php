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

class LoadRadioStation_19_InkomaziFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Nkomazi FM");
        $radio->setBio("Community Radio Station established through the Independent Communications Authority of South Africa (ICASA) Act of 2000 No.13 of 2000, to provide a radio broadcasting service to the whole local Municipality of Nkomazi and other parts of Mpumalanga Province.
 
Nkomazi FM as community radio is a radio service offering a third model of radio broadcasting in addition to commercial and public broadcasting. As a non-profit organization the station is operated, owned and influenced by the community and provides a mechanism for enabling individuals and groups in the community to tell their own stories to share experiences, and in doing so,  become creators and contributors to media.
 
The project itself is the brainchild of the founding member Mr M.R Zulu.  The application for this initiative was formulated and submitted to the ICASA offices in 08/2009, to obtain a broadcasting certificate. Consequently, the ICASA honoured an accreditation to Nkomazi FM to broadcast for a period of five (5) years, due for renewal. The certificate was issued by ICASA on the 18th March 2011.
 
The passion, the drive, the tenacity and the talent of the individuals involved in the station, combined, to bring the station to life, for its first on air broadcast on 10 March 2014.");
        $radio->setFrequency("100.02 FM");
        $radio->setWebsite("http://nkomazifm.co.za/");
        $radio->setStream("http://80.86.80.200:443/");
        $radio->setContactNumber("(061) 496 1970");
        $radio->setContactEmail("info@nkomazifm.co.za");
        $radio->setStatus($this->getReference('status-paused'));
        $radio->setRadioStationType($this->getReference('radio-type-community'));
        $radio->setLogo('nkomazifm-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));
        $radio->addLanguage($this->getReference('lang-swati'));


        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-gospel'));
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-african'));
        $radio->addGenre($this->getReference('genre-afro-pop'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-mpumalanga'));

        $radio->setStreamId('0023');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-inkomazifm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 23;
    }
}