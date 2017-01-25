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

class LoadRecordLabels1Gallo extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $recordLabel->setName("Gallo Record Company");
        $recordLabel->setRegisteredAs("Gallo Record Company");
        $recordLabel->setContactNumber("+27 11 788-0519");
        $recordLabel->setEmail("info@gallo.co.za");
        $recordLabel->setWebsite("http://www.gallo.co.za/");
        $recordLabel->setTwitter("");
        $recordLabel->setFacebook("https://www.facebook.com/gallorecordcompanysa");
        $recordLabel->setSummary("Gallo Record Company is the largest (and oldest independent) record label in South Africa. It is based in Johannesburg, South Africa, and is owned by Times Media Group (formerly Johnnic Communications and Avusa). The current Gallo Record Company is a hybrid of two rival South African record labels between the 1940s and 1980s: the original Gallo Africa (1926–1985) and G.R.C. (Gramophone Record Company, 1939–1985). In 1985 Gallo Africa acquired G.R.C.; as a result, Gallo Africa became known as Gallo-GRC. Five years after the acquisition, the company was renamed Gallo Record Company.

The company owns over 75% of all recordings ever made in South Africa, including those by artists such as Ladysmith Black Mambazo, Mahlathini and the Mahotella Queens, Miriam Makeba, Hugh Masekela, Stimela, West Nkosi, and Makgona Tsohle Band.

Although they both use the rooster as the basis for their logos (since \"Gallo\" is the Italian word for \"rooster\"), the company is not affiliated with the American E and J Gallo Winery.");

        $recordLabel->setCreatedBy($this->getReference('user-admin-ronald'));
        $service->create($recordLabel);
        $this->addReference('record_label_gallo',$recordLabel);

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