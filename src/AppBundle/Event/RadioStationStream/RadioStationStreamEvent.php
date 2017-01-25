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

namespace AppBundle\Event\RadioStationStream;

use AppBundle\Entity\RadioStationStream;
use Symfony\Component\EventDispatcher\Event;

class RadioStationStreamEvent extends Event
{
    /**
     * @var RadioStationStream
     */
    private $radioStationStream;

    /**
     * RadioStationStreamEvent constructor.
     * @param RadioStationStream $radioStationStream
     */
    public function __construct(RadioStationStream $radioStationStream)
    {
        $this->radioStationStream = $radioStationStream;
    }

    /**
     * @return RadioStationStream
     */
    public function getRadioStationStream()
    {
        return $this->radioStationStream;
    }

}