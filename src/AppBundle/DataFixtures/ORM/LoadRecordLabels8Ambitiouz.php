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

class LoadRecordLabels8Ambitiouz extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("Ambitiouz Entertainment");
        $recordLabel->setRegisteredAs("Ambitiouz Entertainment");
        $recordLabel->setContactNumber("");
        $recordLabel->setEmail("info@ambitiouz.co.za");
        $recordLabel->setWebsite("http://ambitiouz.co.za");
        $recordLabel->setTwitter("https://twitter.com/Ambitiouz_Ent");
        $recordLabel->setFacebook("https://www.facebook.com/AmbitiouzEnt/");
        $recordLabel->setSummary("Inspired and defined by a passion Ambitiouz Entertainment is living its affirmation; with a precept  “Defining an Era, Changing lives” Ambitiouz Entertainment has began to chronicle the destiny of these ambitiouz artists. A record and management label, Ambitiouz Entertainment handles young and fresh SA musicians. The label is molding and assisting the artists as they carve their dream to great success for their music and brands, -purely passionate and skilled artists who are absorbed in the creative space so as to offer fresh sounds.
 ");
        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_amb',$recordLabel);

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