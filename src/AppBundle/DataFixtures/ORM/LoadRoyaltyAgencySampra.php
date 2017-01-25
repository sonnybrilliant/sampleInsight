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

class LoadRoyaltyAgencySampra extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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

        $sampra = new RoyaltyAgency();

        $sampra->setName("SAMPRA");
        $sampra->setBio("The South African Music Performance Rights Association (Section 21 limited by guarantee) (SAMPRA) was accredited by the Department of Trade and Industry's Companies and Intellectual Property Registration Office (CIPRO) in June 2007. SAMPRA is a national, non-governmental, organization that licenses to third parties specific copyrights that vest in record companies that are members of the Recording Industry of South Africa (RiSA). The body of sound recordings licensed by SAMPRA is referred to as its repertoire.");

        $sampra->setEmail("info@sampra.org.za");
        $sampra->setContactNumber("+27 11 789-5784");
        $sampra->setFacebook("");
        $sampra->setTwitter("");
        $sampra->setWebsite("http://www.sampra.org.za/");
        $sampra->setCreatedBy($this->getReference('user-admin-ronald'));
        $sampra->setStatus($this->getReference('status-active'));
        $service->create($sampra);
        $this->addReference('royalty_agency_sampra',$sampra);

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