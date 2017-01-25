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
use AppBundle\Entity\RoyaltyAgency;

class LoadRoyaltyAgencyCWUSA extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $service = $this->container->get('app.service.royalty_agency');

        $cwusa = new RoyaltyAgency();

        $cwusa->setName("Creative Workers Union of South Africa (CWUSA)");
        $cwusa->setBio("The Creative Workers Union of South Africa (CWUSA) is a trade union in South Africa. It is affiliated with the Congress of South African Trade Unions (COSATU).");

        $cwusa->setEmail("generalsecretary.cwusa@gmail.com");
        $cwusa->setContactNumber("010-591-0227");
        $cwusa->setFacebook("https://www.facebook.com/groups/cwusa/");
        $cwusa->setTwitter("https://twitter.com/hashtag/CWUSA");
        $cwusa->setWebsite("http://www.samro.org.za/node/15942");
        $cwusa->setCreatedBy($this->getReference('user-admin-ronald'));
        $cwusa->setStatus($this->getReference('status-active'));
        $service->create($cwusa);
        $this->addReference('royalty_agency_cwusa',$cwusa);

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