<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/01
 */

namespace AppBundle\Event\Artist;

use AppBundle\Entity\Artist;
use Symfony\Component\EventDispatcher\Event;

class ArtistEvent extends Event
{
    /**
     * @var Artist
     */
    private $artist;

    /**
     * ArtistEvent constructor.
     * @param Artist $artist
     */
    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

}