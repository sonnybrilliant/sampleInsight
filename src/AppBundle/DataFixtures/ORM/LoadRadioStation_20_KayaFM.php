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

class LoadRadioStation_20_KayaFM extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $radio->setName("Kaya FM");
        $radio->setBio("KAYA FM reflects the lives of the predominantly black, urban listener between the ages 25 – 49 living in Gauteng. The station broadcasts both music and talk. KAYA FM 95.9 broadcasts in English on the FM frequency signal 95 (Dot) 9, 24 hours a day, seven days a week. The current listenership stands at 462 000 per average day and 841 000 per average 7 days. The music format offers a diverse and soulful mix of adult contemporary music to smoother sounds like R&B, World Music and Soul and Jazz.");
        $radio->setFrequency("95.9 FM");
        $radio->setWebsite("http://www.kayafm.co.za/");
        $radio->setStream("rtmp://196.33.130.82/kayafm_failover/kayafm_failover.stream");
        $radio->setContactNumber("(011) 634 9500");
        $radio->setContactEmail("info@kayafm.co.za");
        $radio->setStatus($this->getReference('status-active'));
        $radio->setRadioStationType($this->getReference('radio-type-commercial'));
        $radio->setLogo('kayafm-icon.png');
        //languages
        $radio->addLanguage($this->getReference('lang-english'));

        //Genres (Popular)
        $radio->addGenre($this->getReference('genre-rnb'));
        $radio->addGenre($this->getReference('genre-jazz'));
        $radio->addGenre($this->getReference('genre-pop'));
        $radio->addGenre($this->getReference('genre-african'));
        $radio->addGenre($this->getReference('genre-afro-pop'));
        $radio->addGenre($this->getReference('genre-kwaito'));
        $radio->addGenre($this->getReference('genre-house'));

        //Broadcast Areas
        $radio->addProvince($this->getReference('province-gauteng'));

        $radio->setStreamId('9442');
        $radio->setCreatedBy($this->getReference('user-admin-ronald'));
        $manager->persist($radio);
        $manager->flush();

        $this->setReference('radio_station-kayafm',$radio);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 24;
    }
}