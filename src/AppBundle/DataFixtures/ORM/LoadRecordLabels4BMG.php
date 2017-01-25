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

class LoadRecordLabels4BMG extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("BMG Records Africa");
        $recordLabel->setRegisteredAs("BMG Records Africa (Pty) Ltd");
        $recordLabel->setContactNumber("+27 11 274-5000");
        $recordLabel->setEmail("info@Sonybmg.co.za");
        $recordLabel->setWebsite("Sonybmg.co.za");
        $recordLabel->setTwitter("");
        $recordLabel->setFacebook("https://www.facebook.com/Sony-BMG-48687053832/");
        $recordLabel->setSummary("In 1992, two years after the release of Nelson Mandela from prison, BMG became the second of the world's major record companies to open/resume operations in South Africa. 
Prior to this, the SA market was controlled between EMI (who handled BMG's distribution), Gallo Africa, and Tusk Music. 

Based in Johannesburg, with branches in Cape Town and Durban, BMG Records Africa (Pty) Ltd. covers the entire subcontinent and the Indian Ocean Islands, encompassing 14 countries in all: Angola, Botswana, Lesotho, Madagascar, Malawi, Mauritius, Mozambique, Namibia, the Reunion Islands, Seychelles, South Africa, Swaziland, Zambia, and Zimbabwe. The company has recorded in 13 languages, ranging from English to Afrikaans, Zulu, Tsonga, and Malagash. 

BMG has since merged with Sony in 2004.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_bmg',$recordLabel);

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