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

class LoadRecordLabels2Kalawa extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("Kalawa Jazmee Records");
        $recordLabel->setRegisteredAs("Kalawa Jazmee Records");
        $recordLabel->setContactNumber("+27 11 656-3574");
        $recordLabel->setEmail("info@kalawa.co.za");
        $recordLabel->setWebsite("http://www.kalawa.co.za");
        $recordLabel->setTwitter("");
        $recordLabel->setFacebook("https://www.facebook.com/Kalawa-Jazmee-Records-120808691513");
        $recordLabel->setSummary("Kalawa Jazmee (sometimes \"KJ Records\") is an independent South African label that was instrumental in the development of the musical style later to be known as \"Kwaito\". 

It was formed as Kalawa in 1992 by Christos Katsaitis (departed in 1995), Don Laka and DJ Oskido and took its name from the first two letters of their respective surnames (Oskido often used \"Warona\" at the time). Boom Shaka were the new label's first signing, with debut album \"It’s About Time\" released in 1993. 
Joining with Trompie's label Jazmee in 1995 it then became known as Kalawa-Jazmee Records, later dropping the hyphen completely. 

The company is currently run by Oscar Mdlongwa, Bruce Sebitlo, Zayne ‘Mahoota’ Sibiya, Mandla ‘Spikiri’ Mofokeng, and Emmanuel ‘Mjokes’ Matsane, with one Gao Mokone assuming the task of Label Manager.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_kalawa',$recordLabel);

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