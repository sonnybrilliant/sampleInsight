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
use AppBundle\Entity\UserGroup;

/**
 * Class LoadUserGroupRadioCompiler
 * @package AppBundle\DataFixtures\ORM
 *
 */
class LoadUserGroupRadioCompiler extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groupAdmin = new UserGroup("Compiler");

        /**
         * Artist related
         */
        $groupAdmin->addRole($this->getReference('role_artist_view'));
        $groupAdmin->addRole($this->getReference('role_user'));

        /**
         * Song related
         */
        $groupAdmin->addRole($this->getReference('role_song_view'));
        $groupAdmin->addRole($this->getReference('role_song_approve_radio_queue'));

        /**
         * Radio station related
         */
        $groupAdmin->addRole($this->getReference('role_radio_station_view'));
        $groupAdmin->addRole($this->getReference('role_radio_station_create_playlist'));

        /**
         * Compiler related
         */
        $groupAdmin->addRole($this->getReference('role_compiler_view'));

        /**
         * Record label related
         */
        $groupAdmin->addRole($this->getReference('role_record_label_view'));

        /**
         * Royalty agency related
         */
        $groupAdmin->addRole($this->getReference('role_royalty_agency_edit'));

        /**
         * Monitoring related
         */
        $groupAdmin->addRole($this->getReference('role_monitor_view'));

        /**
         * Advertising related
         */
        $groupAdmin->addRole($this->getReference('role_advert_view'));
        $groupAdmin->addRole($this->getReference('role_advert_edit'));

        $manager->persist($groupAdmin);
        $manager->flush();
        $this->addReference('group-compiler',$groupAdmin);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

}