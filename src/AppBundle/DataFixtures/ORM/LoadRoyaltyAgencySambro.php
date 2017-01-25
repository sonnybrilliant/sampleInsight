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

class LoadRoyaltyAgencySambro extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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

        $sambro = new RoyaltyAgency();

        $sambro->setName("SAMRO");
        $sambro->setBio("Music has always had a big effect on culture throughout history. From struggle songs to sports anthems, music has the power to move people. Influencing politics, faith, fashion and popular culture. Music doesn't just make for a better dance scene - it’s the soundtrack to our past and our future. So it’s incredibly important that we protect our musical talent and heritage. And encourage new music creators to join the party. 

Since 1961, SAMRO has been South Africa’s music rights champion. We protect the rights of composers and authors (music creators) both locally and internationally. Collecting licence fees from music users – television broadcasters, radio stations, in-store radio stations, pubs, clubs, retailers, restaurants and all other businesses that broadcast, use or play music. 

Five decades of experience managing music rights has given us a high-definition picture of the way music is used out there. And we use this knowledge to assess fair and reasonable royalties on a track by track basis – looking at when and where each piece of music is used. We pass on these royalties to the talented folks who play a role in creating the music we all love to share.");

        $sambro->setEmail("customerservices@samro.org.za");
        $sambro->setContactNumber("+27 (0) 86 117 2676 ");
        $sambro->setFacebook("https://www.facebook.com/SAMROSouthAfrica");
        $sambro->setTwitter("http://twitter.com/@SAMROMusic");
        $sambro->setWebsite("http://www.samro.org.za");
        $sambro->setCreatedBy($this->getReference('user-admin-ronald'));
        $sambro->setStatus($this->getReference('status-active'));
        $service->create($sambro);
        $this->addReference('royalty_agency_sambro',$sambro);

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