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
use AppBundle\Entity\Genre;

/**
 *
 * Class LoadGenres
 * @package AppBundle\DataFixtures\ORM
 *
 */
class LoadGenres extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $genre_acoustic = new Genre('acoustic');
        $manager->persist($genre_acoustic);

        $genre_afro_pop = new Genre('afro-pop');
        $manager->persist($genre_afro_pop);
        $this->setReference('genre-afro-pop', $genre_afro_pop);

        $genre_african = new Genre('african');
        $manager->persist($genre_african);
        $this->setReference('genre-african',$genre_african);

        $genre_alternative = new Genre('alternative');
        $manager->persist($genre_alternative);

        $genre_anime = new Genre('anime');
        $manager->persist($genre_anime);

        $genre_blues = new Genre('blues');
        $manager->persist($genre_blues);

        $genre_childrens_music = new Genre("children's-music");
        $manager->persist($genre_childrens_music);

        $genre_christian = new Genre('christian');
        $manager->persist($genre_christian);

        $genre_classical = new Genre('classical');
        $manager->persist($genre_classical);

        $genre_classic_rock = new Genre('classic-rock');
        $manager->persist($genre_classic_rock);

        $genre_comedy = new Genre('comedy');
        $manager->persist($genre_comedy);

        $genre_country = new Genre('country');
        $manager->persist($genre_country);

        $genre_dance = new Genre('dance');
        $manager->persist($genre_dance);

        $genre_easy_listening = new Genre('easy-listening');
        $manager->persist($genre_easy_listening);

        $genre_electronic = new Genre('electronic');
        $manager->persist($genre_electronic);

        $genre_enka = new Genre('enka');
        $manager->persist($genre_enka);

        $genre_female_vocalists = new Genre('female-vocalists');
        $manager->persist($genre_female_vocalists);

        $genre_folk = new Genre('folk');
        $manager->persist($genre_folk);

        $genre_funk = new Genre('funk');
        $manager->persist($genre_funk);

        $genre_hip_hop = new Genre('hip-hop');
        $manager->persist($genre_hip_hop);
        $this->setReference('genre-hip-hop', $genre_hip_hop);

        $genre_indie = new Genre('indie');
        $manager->persist($genre_indie);

        $genre_indie_pop = new Genre('indie-pop');
        $manager->persist($genre_indie_pop);

        $genre_gospel = new Genre('gospel');
        $manager->persist($genre_gospel);
        $this->addReference('genre-gospel',$genre_gospel);

        $genre_house = new Genre('house');
        $manager->persist($genre_house);
        $this->addReference('genre-house',$genre_house);

        $genre_instrumental = new Genre('instrumental');
        $manager->persist($genre_instrumental);

        $genre_jazz = new Genre('jazz');
        $manager->persist($genre_jazz);
        $this->addReference('genre-jazz',$genre_jazz);

        $genre_jazz_vocal = new Genre('jazz-vocal');
        $manager->persist($genre_jazz_vocal);

        $genre_kwaito = new Genre('kwaito');
        $manager->persist($genre_kwaito);
        $this->setReference('genre-kwaito', $genre_kwaito);

        $genre_latino = new Genre('latino');
        $manager->persist($genre_latino);

        $genre_new_age = new Genre('new age');
        $manager->persist($genre_new_age);

        $genre_opera = new Genre('opera');
        $manager->persist($genre_opera);

        $genre_pop = new Genre('pop');
        $manager->persist($genre_pop);
        $this->setReference('genre-pop', $genre_pop);

        $genre_rnb = new Genre('rnb');
        $manager->persist($genre_rnb);
        $this->setReference('genre-rnb', $genre_rnb);

        $genre_rap = new Genre('rap');
        $manager->persist($genre_rap);
        $this->setReference('genre-rap', $genre_rap);

        $genre_soul = new Genre('soul');
        $manager->persist($genre_soul);

        $genre_reggae = new Genre('reggae');
        $manager->persist($genre_reggae);

        $genre_rock = new Genre('rock');
        $manager->persist($genre_rock);
        $this->setReference('genre-rock', $genre_rock);

        $genre_singer_songwriter = new Genre('singer-songwriter');
        $manager->persist($genre_singer_songwriter);

        $genre_soundtrack = new Genre('soundtrack');
        $manager->persist($genre_soundtrack);

        $genre_traditional = new Genre('traditional');
        $manager->persist($genre_traditional);
        $this->addReference('genre-traditional',$genre_traditional);

        $genre_vocal = new Genre('vocal');
        $manager->persist($genre_vocal);

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