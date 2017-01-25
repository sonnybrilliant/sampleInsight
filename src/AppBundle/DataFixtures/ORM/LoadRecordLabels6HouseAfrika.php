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

class LoadRecordLabels6HouseAfrika extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("House Afrika Records");
        $recordLabel->setRegisteredAs("House Afrika Records");
        $recordLabel->setContactNumber("072 593 8049");
        $recordLabel->setEmail("info@houseafrika.com");
        $recordLabel->setWebsite("http://www.houseafrika.com/");
        $recordLabel->setTwitter("https://twitter.com/house_afrika");
        $recordLabel->setFacebook("https://www.facebook.com/houseafrikarecords");
        $recordLabel->setSummary("House Afrika Records has been at the forefront of the South African dance music scene for two decades, cementing deep house culture in the country’s consciousness, sparking the careers of a number of name acts and spawning the massive market for top-notch, beat-driven compilations helmed by local and international legends.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_house',$recordLabel);

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