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

namespace AppBundle\Event\Song;

use AppBundle\Entity\Song;
use Symfony\Component\EventDispatcher\Event;

class SongEvent extends Event
{
    /**
     * @var Song
     */
    private $song;

    /**
     * SongEvent constructor.
     * @param Song $song
     */
    public function __construct(Song $song)
    {
        $this->song = $song;
    }

    /**
     * @return Song
     */
    public function getSong()
    {
        return $this->song;
    }

}