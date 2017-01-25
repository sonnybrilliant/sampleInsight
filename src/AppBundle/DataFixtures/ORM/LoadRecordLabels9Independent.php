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

class LoadRecordLabels9Independent extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("Independent label");
        $recordLabel->setRegisteredAs("Independent Label(For Acts without record label representation)");
        $recordLabel->setContactNumber("+27 11 021 0822");
        $recordLabel->setEmail("business@ocmafrica.com");
        $recordLabel->setWebsite("http://www.ocmafrica.com/");
        $recordLabel->setTwitter("https://twitter.com/ocmafrica");
        $recordLabel->setFacebook("https://www.facebook.com/OCMafrica/");
        $recordLabel->setSummary("OCManagement (OCM) is a proudly 100% black owned management and solutions agency for African businesses, artists and projects/campaigns. Our core focus is strategy, development and management solutions.

We started as a Talent Development and Management Company in 2014 then grew into a powerhouse agency successfully venturing into Brand development, Campaigns, Events, Activations, development, facilitation and management.

Formerly registered in March 2015, we experienced rapid and enormous growth since inception; signing 6 acts that broke through into the mainstream entertainment space.  OCManagement has numerous corporate engagements and collaborations under its belt. Having serviced and worked with giants such as Fresh Eye Films, Universal Music, Sony Music Africa, Jazzworx, Bill and Melinda Gates Foundation, Oxfam and Sony/ATV, we have proven to also play in the big leagues.

2015 ended with us successfully managing to stage our first ever festival in Limpopo(Venda), the event “Venda Nga Decemba” attracted an attendance of over 8000 people with only 1 headlining act and local performances.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_ocm',$recordLabel);

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