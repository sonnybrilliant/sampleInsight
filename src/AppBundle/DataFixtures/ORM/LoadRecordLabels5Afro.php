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
use AppBundle\Entity\RecordLabel;

class LoadRecordLabels5Afro extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $service = $this->container->get('app.service.record_label');

        $recordLabel = new RecordLabel();
        $recordLabel->setName("West Ink / Afrotainment");
        $recordLabel->setRegisteredAs("Afrotainment");
        $recordLabel->setContactNumber("+27 31 309 4643");
        $recordLabel->setEmail("events@afrotainment.co.za");
        $recordLabel->setWebsite("www.afrotainment.co.za");
        $recordLabel->setTwitter("https://twitter.com/@DJTira");
        $recordLabel->setFacebook("https://www.facebook.com/Afrotainment-SA-865584276810096/");
        $recordLabel->setSummary("Afrotainment is your one stop entertainment shop. We supply everything from artists performance, sound,  staging, lighting, advertising and marketing. We have our own recording studios where we record adverts, theme songs and just music production. We did the STATUS ad music with Doctor Khumalo, Nedbank Cup kwaito Theme song, Department of Transport safety campaign songs feat. Tzozo En Professor and Ihhashi Elimhlophe. Call us now and enter our world of showbiz!");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_afro',$recordLabel);

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}