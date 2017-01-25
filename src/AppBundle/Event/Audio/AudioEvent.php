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

namespace AppBundle\Event\Audio;

use AppBundle\Common\Audio;
use Symfony\Component\EventDispatcher\Event;

class AudioEvent extends Event
{
    /**
     * @var Audio
     */
    private $audio;

    /**
     * AudioEvent constructor.
     * @param Audio $audio
     */
    public function __construct(Audio $audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return Audio
     */
    public function getAudio()
    {
        return $this->audio;
    }

}