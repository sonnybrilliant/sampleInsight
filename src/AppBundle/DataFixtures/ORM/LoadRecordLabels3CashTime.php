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

class LoadRecordLabels3CashTime extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("CashTime Life");
        $recordLabel->setRegisteredAs("CashTime");
        $recordLabel->setContactNumber("");
        $recordLabel->setEmail("bookings@cashtimelife.com");
        $recordLabel->setWebsite("http://www.cashtimelife.com");
        $recordLabel->setTwitter("https://twitter.com/cashtimelife");
        $recordLabel->setFacebook("https://www.facebook.com/CashtimeTsotsi4Life");
        $recordLabel->setSummary("CashTime Life is a South African Music Publishing Company and Entertainment Company, as well as a record label and Management Service. The CashTime Fam is a South African hip hop collective, consisting of South African hip hop musicians, performers and entertainers K.O, Ma-E, Kid X, Maggz, DJ Vigilante and their only R&B singer Masandi.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_cashtime',$recordLabel);

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