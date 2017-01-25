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
use AppBundle\Entity\Role;

/**
 * Class LoadRoles
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRoles extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $role_admin = new Role("Super admin","ROLE_ADMIN");
        $manager->persist($role_admin);
        $this->addReference('role_admin',$role_admin);

        $role_switch = new Role("Allow Switch","ROLE_ALLOWED_TO_SWITCH");
        $manager->persist($role_switch);
        $this->addReference('role_switch',$role_switch);

        $role_user = new Role("Role user","ROLE_USER");
        $manager->persist($role_user);
        $this->addReference('role_user',$role_user);


        /**
         * Artist Related Roles
         */
        $role_artist_edit = new Role("Role edit artist","ROLE_ARTIST_EDIT");
        $manager->persist($role_artist_edit);
        $this->addReference('role_artist_edit',$role_artist_edit);

        $role_artist_view = new Role("Role view artist","ROLE_ARTIST_VIEW");
        $manager->persist($role_artist_view);
        $this->addReference('role_artist_view',$role_artist_view);


        /**
         * Song Related Roles
         */
        $role_song_edit = new Role("Role edit song","ROLE_SONG_EDIT");
        $manager->persist($role_song_edit);
        $this->addReference('role_song_edit',$role_song_edit);

        $role_song_view = new Role("Role view song","ROLE_SONG_VIEW");
        $manager->persist($role_song_view);
        $this->addReference('role_song_view',$role_song_view);

        $role_song_approve = new Role("Role approve song","ROLE_SONG_APPROVE");
        $manager->persist($role_song_approve);
        $this->addReference('role_song_approve',$role_song_approve);

        $role_song_approve_radio_queue = new Role("Role approve song to radio queue","ROLE_SONG_APPROVE_RADIO_QUEUE");
        $manager->persist($role_song_approve_radio_queue);
        $this->addReference('role_song_approve_radio_queue',$role_song_approve_radio_queue);

        /**
         * Radio station related roles
         */
        $role_radio_station_admin = new Role("Role radio station admin","ROLE_RADIO_STATION_ADMINISTRATOR");
        $manager->persist($role_radio_station_admin);
        $this->addReference('role_radio_station_admin',$role_radio_station_admin);


        $role_radio_station_edit = new Role("Role edit radio station","ROLE_RADIO_STATION_EDIT");
        $manager->persist($role_radio_station_edit);
        $this->addReference('role_radio_station_edit',$role_radio_station_edit);

        $role_radio_station_view = new Role("Role view radio station","ROLE_RADIO_STATION_VIEW");
        $manager->persist($role_radio_station_view);
        $this->addReference('role_radio_station_view',$role_radio_station_view);

        $role_radio_station_create_playlist = new Role("Role radio station playlist","ROLE_RADIO_STATION_CREATE_PLAYLIST");
        $manager->persist($role_radio_station_create_playlist);
        $this->addReference('role_radio_station_create_playlist',$role_radio_station_create_playlist);

        /**
         * Compiler related roles
         */
        $role_compiler_edit = new Role("Role edit compiler","ROLE_COMPILER_EDIT");
        $manager->persist($role_compiler_edit);
        $this->addReference('role_compiler_edit',$role_compiler_edit);

        $role_compiler_view = new Role("Role view compiler","ROLE_COMPILER_VIEW");
        $manager->persist($role_compiler_view);
        $this->addReference('role_compiler_view',$role_compiler_view);


        /**
         * Record label related roles
         */
        $role_record_label_edit = new Role("Role edit record label","ROLE_RECORD_LABEL_EDIT");
        $manager->persist($role_record_label_edit);
        $this->addReference('role_record_label_edit',$role_record_label_edit);

        $role_record_label_view = new Role("Role view record label","ROLE_RECORD_LABEL_VIEW");
        $manager->persist($role_record_label_view);
        $this->addReference('role_record_label_view',$role_record_label_view);

        /**
         * Royalty agency related roles
         */
        $role_royalty_agency_edit = new Role("Role edit royalty agency","ROLE_ROYALTY_AGENCY_EDIT");
        $manager->persist($role_royalty_agency_edit);
        $this->addReference('role_royalty_agency_edit',$role_royalty_agency_edit);

        $role_royalty_agency_view = new Role("Role view royalty agency","ROLE_ROYALTY_AGENCY_VIEW");
        $manager->persist($role_royalty_agency_view);
        $this->addReference('role_royalty_agency_view',$role_royalty_agency_view);
        /**
         * Reports related roles
         */

        /**
         * Monitoring related roles
         */
        $role_monitor_view = new Role("Role monitoring view","ROLE_MONITOR_VIEW");
        $manager->persist($role_monitor_view);
        $this->addReference('role_monitor_view',$role_monitor_view);

        $role_monitor_edit = new Role("Role monitoring edit","ROLE_MONITOR_EDIT");
        $manager->persist($role_monitor_edit);
        $this->addReference('role_monitor_edit',$role_monitor_edit);

        /**
         * Adverting role
         */
        $role_advert_view = new Role("Role advert view","ROLE_ADVERT_VIEW");
        $manager->persist($role_advert_view );
        $this->addReference('role_advert_view',$role_advert_view);

        $role_advert_edit = new Role("Role advert edit","ROLE_ADVERT_EDIT");
        $manager->persist($role_advert_edit );
        $this->addReference('role_advert_edit',$role_advert_edit);

        /**
         * Show role
         */
        $role_show_view = new Role("Role show view","ROLE_SHOW_VIEW");
        $manager->persist($role_show_view);
        $this->addReference('role_show_view',$role_show_view);

        $role_show_edit = new Role("Role show edit","ROLE_SHOW_EDIT");
        $manager->persist($role_show_edit );
        $this->addReference('role_show_edit',$role_show_edit);

        /**
         * Promo roles
         */
        $role_promo_view = new Role("Role promo view","ROLE_PROMO_VIEW");
        $manager->persist($role_promo_view);
        $this->addReference('role_promo_view',$role_promo_view);

        $role_promo_edit = new Role("Role promo edit","ROLE_PROMO_EDIT");
        $manager->persist($role_promo_edit );
        $this->addReference('role_promo_edit',$role_promo_edit);

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