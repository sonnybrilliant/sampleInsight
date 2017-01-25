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

class LoadRoyaltyAgencyRisa extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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

        $risa = new RoyaltyAgency();

        $risa->setName("RiSA");
        $risa->setBio("The Recording Industry of South Africa (RiSA) is a trade association that represents the collective interests of producers of music sound recordings, major and independent record labels in South Africa. Formerly known as the Association of the South African Music Industry (ASAMI) it was established in the 1970s. The association consists of approximately 3,000 members, including the big three record labels, Sony Music, Universal Music and Warner Music. 

RiSA is responsible for running the annual South African Music Awards (SAMAs) and for acknowledging certification awards for album sales. RiSA is recognised by the International Federation of the Phonographic Industry as the official National Group for the Recording Industry in South Africa. RiSA is also the association in South Africa responsible for issuing the International Standard Recording Codes (ISRC).");

        $risa->setEmail("risa@risa.org.za");
        $risa->setContactNumber("+27 11 886 1342");
        $risa->setFacebook("https://www.facebook.com/pages/Recording-Industry-of-South-Africa/137176862968091");
        $risa->setTwitter("");
        $risa->setWebsite("http://www.risa.org.za/");
        $risa->setCreatedBy($this->getReference('user-admin-ronald'));
        $risa->setStatus($this->getReference('status-active'));
        $service->create($risa);
        $this->addReference('royalty_agency_risa',$risa);

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