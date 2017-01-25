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
use AppBundle\Entity\Industry;

/**
 * Class LoadIndustry
 * @package AppBundle\DataFixtures\ORM
 */
class LoadIndustry extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $industry_automotive = new Industry("Automotive");
        $manager->persist($industry_automotive);
        $this->addReference('industry-automotive',$industry_automotive);

        $industry_asset = new Industry("Asset and Wealth Management");
        $manager->persist($industry_asset);
        $this->addReference('industry-asset',$industry_asset);

        $industry_banking= new Industry("Banking & Capital Markets");
        $manager->persist($industry_banking);
        $this->addReference('industry-banking',$industry_banking);

        $industry_chemicals = new Industry("Chemicals");
        $manager->persist($industry_chemicals);
        $this->addReference('industry-chemicals',$industry_chemicals);

        $industry_defence = new Industry("Defence & Security");
        $manager->persist($industry_defence);
        $this->addReference('industry-defence',$industry_defence);

        $industry_energy = new Industry("Energy, Utilities & Mining");
        $manager->persist($industry_energy);
        $this->addReference('industry-energy',$industry_energy);

        $industry_engineering = new Industry("Engineering & Construction");
        $manager->persist($industry_engineering);
        $this->addReference('industry-engineering',$industry_engineering);

        $industry_entertainment = new Industry("Entertainment & Media");
        $manager->persist($industry_entertainment);
        $this->addReference('industry-entertainment',$industry_entertainment);

        $industry_financial = new Industry("Financial Services");
        $manager->persist($industry_financial);
        $this->addReference('industry-financial',$industry_financial);

        $industry_forest= new Industry("Forest, paper & packaging");
        $manager->persist($industry_forest);
        $this->addReference('industry-forest',$industry_forest);

        $industry_government = new Industry("Government and Public Services");
        $manager->persist($industry_government);
        $this->addReference('industry-government',$industry_government);

        $industry_healthcare = new Industry("Healthcare");
        $manager->persist($industry_healthcare);
        $this->addReference('industry-healthcare',$industry_healthcare);

        $industry_higher = new Industry("Higher Education");
        $manager->persist($industry_higher);
        $this->addReference('industry-higher-education',$industry_higher);

        $industry_hospitality = new Industry("Hospitality & Leisure");
        $manager->persist($industry_hospitality);
        $this->addReference('industry-hospitality',$industry_hospitality);

        $industry_international = new Industry("International Development Assistance (IDA)");
        $manager->persist($industry_international);
        $this->addReference('industry-international',$industry_international);

        $industry_industrial = new Industry("Industrial Manufacturing");
        $manager->persist($industry_industrial);
        $this->addReference('industry-industrial',$industry_industrial);

        $industry_insurance = new Industry("Insurance");
        $manager->persist($industry_insurance);
        $this->addReference('industry-insurance',$industry_insurance);

        $industry_medical = new Industry("Medical Schemes");
        $manager->persist($industry_medical);
        $this->addReference('industry-medical',$industry_medical);

        $industry_metals = new Industry("Metals");
        $manager->persist($industry_metals);
        $this->addReference('industry-metals',$industry_metals);

        $industry_pharmaceuticals = new Industry("Pharmaceuticals");
        $manager->persist($industry_pharmaceuticals);
        $this->addReference('industry-pharmaceuticals',$industry_pharmaceuticals);

        $industry_public = new Industry("Public Sector");
        $manager->persist($industry_public);
        $this->addReference('industry-public',$industry_public);

        $industry_retail = new Industry("Retail & Consumer");
        $manager->persist($industry_retail);
        $this->addReference('industry-retail',$industry_retail);

        $industry_retirement = new Industry("Retirement Funds");
        $manager->persist($industry_retirement);
        $this->addReference('industry-retirement',$industry_retirement);

        $industry_technology = new Industry("Technology");
        $manager->persist($industry_technology);
        $this->addReference('industry-technology',$industry_technology);

        $industry_telecommunications = new Industry("Telecommunications");
        $manager->persist($industry_telecommunications);
        $this->addReference('industry-telecommunications',$industry_telecommunications);

        $industry_transportation = new Industry("Transportation & Logistics");
        $manager->persist($industry_transportation);
        $this->addReference('industry-transportation',$industry_transportation);

        $industry_mining = new Industry("Mining");
        $manager->persist($industry_mining);
        $this->addReference('industry-mining',$industry_telecommunications);

        $manager->flush();

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }


}