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
use AppBundle\Entity\Language;

/**
 * Class LoadLanguages
 * @package AppBundle\DataFixtures\ORM
 */
class LoadLanguages extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $afrikaans = new Language("afrikaans");
        $manager->persist($afrikaans);
        $this->setReference('lang-afrikaans',$afrikaans);

        $english = new Language("english");
        $manager->persist($english);
        $this->setReference('lang-english',$english);

        $ndebele = new Language("Ndebele");
        $manager->persist($ndebele);
        $this->setReference('lang-ndebele',$ndebele);

        $northSotho = new Language("north sotho");
        $manager->persist($northSotho);
        $this->setReference('lang-north-sotho',$northSotho);

        $southSotho = new Language("south sotho");
        $manager->persist($southSotho);
        $this->setReference('lang-south-sotho',$southSotho);

        $swati = new Language("swati");
        $manager->persist($swati);
        $this->setReference('lang-swati',$swati);

        $tsonga = new Language("tsonga");
        $manager->persist($tsonga);
        $this->setReference('lang-tsonga',$tsonga);

        $tswana = new Language("tswana");
        $manager->persist($tswana);
        $this->setReference('lang-tswana',$tswana);

        $venda = new Language("venda");
        $manager->persist($venda);
        $this->setReference('lang-venda',$venda);

        $xhosa = new Language("xhosa");
        $manager->persist($xhosa);
        $this->setReference('lang-xhosa',$xhosa);

        $zulu = new Language("zulu");
        $manager->persist($zulu);
        $this->setReference('lang-zulu',$zulu);

        $french = new Language("french");
        $manager->persist($french);
        $this->setReference('lang-french',$french);

        $Kiswahili = new Language("kiswahili");
        $manager->persist($Kiswahili);
        $this->setReference('lang-kiswahili',$Kiswahili);

        $portuguese = new Language("portuguese");
        $manager->persist($portuguese);
        $this->setReference('lang-portuguese',$portuguese);

        $silozi= new Language("silozi");
        $manager->persist($silozi);
        $this->setReference('lang-silozi',$silozi);

        $chinyanja= new Language("chinyanja");
        $manager->persist($chinyanja);
        $this->setReference('lang-chinyanja',$chinyanja);

        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }

}