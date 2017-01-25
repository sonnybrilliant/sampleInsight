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

class LoadRecordLabels7BoomStudio extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("Boom.Studio");
        $recordLabel->setRegisteredAs("Boom.Studio");
        $recordLabel->setContactNumber("");
        $recordLabel->setEmail("");
        $recordLabel->setWebsite("https://boom.studio");
        $recordLabel->setTwitter("https://twitter.com/boomstudios");
        $recordLabel->setFacebook("");
        $recordLabel->setSummary("We’re breaking the mould of traditional, old-school record labels and making sure artists and their fans are empowered and rewarded.

We’ve created a truly unique home where today’s musicians and bands can connect with their fans. 

Our artists are supported by a creative, transparent and reliable infrastructure that takes care of crossing the t’s and dotting the i’s, so they can do what they do best – make music for their fans. ");
        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_boom',$recordLabel);

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