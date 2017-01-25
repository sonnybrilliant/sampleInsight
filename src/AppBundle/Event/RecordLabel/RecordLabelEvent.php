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

namespace AppBundle\Event\RecordLabel;

use AppBundle\Entity\RecordLabel;
use Symfony\Component\EventDispatcher\Event;

class RecordLabelEvent extends Event
{
    /**
     * @var RecordLabel
     */
    private $recordLabel;

    /**
     * RecordLabelEvent constructor.
     * @param RecordLabel $recordLabel
     */
    public function __construct(RecordLabel $recordLabel)
    {
        $this->recordLabel = $recordLabel;
    }

    /**
     * @return RecordLabel
     */
    public function getRecordLabel()
    {
        return $this->recordLabel;
    }

}