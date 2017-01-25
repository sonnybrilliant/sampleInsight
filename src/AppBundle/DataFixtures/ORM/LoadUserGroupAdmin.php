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
 * Class LoadAdminUserGroup
 * @package AppBundle\DataFixtures\ORM
 *
 */
class LoadAdminUserGroup extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groupAdmin = new UserGroup("Administrator");
        $groupAdmin->addRole($this->getReference('role_admin'));
        $groupAdmin->addRole($this->getReference('role_switch'));

        /**
         * Artist related
         */
        $groupAdmin->addRole($this->getReference('role_artist_edit'));
        $groupAdmin->addRole($this->getReference('role_artist_view'));

        /**
         * Song related
         */
        $groupAdmin->addRole($this->getReference('role_song_edit'));
        $groupAdmin->addRole($this->getReference('role_song_view'));
        $groupAdmin->addRole($this->getReference('role_song_approve'));
        $groupAdmin->addRole($this->getReference('role_song_approve_radio_queue'));

        /**
         * Radio station related
         */
        $groupAdmin->addRole($this->getReference('role_radio_station_edit'));
        $groupAdmin->addRole($this->getReference('role_radio_station_view'));
        $groupAdmin->addRole($this->getReference('role_radio_station_admin'));
        $groupAdmin->addRole($this->getReference('role_radio_station_create_playlist'));

        /**
         * Compiler related
         */
        $groupAdmin->addRole($this->getReference('role_compiler_edit'));
        $groupAdmin->addRole($this->getReference('role_compiler_view'));

        /**
         * Record label related
         */
        $groupAdmin->addRole($this->getReference('role_record_label_edit'));
        $groupAdmin->addRole($this->getReference('role_record_label_view'));

        /**
         * Royalty agency related
         */
        $groupAdmin->addRole($this->getReference('role_royalty_agency_edit'));
        $groupAdmin->addRole($this->getReference('role_royalty_agency_view'));

        /**
         * Reports related
         */

        /**
         * Monitoring related
         */
        $groupAdmin->addRole($this->getReference('role_monitor_view'));
        $groupAdmin->addRole($this->getReference('role_monitor_edit'));

        /**
         * Advertising related
         */
        $groupAdmin->addRole($this->getReference('role_advert_view'));
        $groupAdmin->addRole($this->getReference('role_advert_edit'));

        /**
         * Shows related
         */
        $groupAdmin->addRole($this->getReference('role_show_view'));
        $groupAdmin->addRole($this->getReference('role_show_edit'));

        /**
         * Promos related
         */
        $groupAdmin->addRole($this->getReference('role_promo_view'));
        $groupAdmin->addRole($this->getReference('role_promo_edit'));

        $manager->persist($groupAdmin);

        $manager->flush();
        $this->addReference('group-admin',$groupAdmin);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

}