<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/06
 */

namespace AppBundle\Handler\RadioStation;

use AppBundle\Service\RadioStation\RadioStationIncomingQueueService;
use AppBundle\Event\Song\SongEvent;
use AppBundle\Event\Song\SongEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Entity\Song;
use AppBundle\Entity\RadioStationQueue;
use AppBundle\Service\Core\StatusServices;

class RadioStationIncomingQueueApproveHandler
{
    /**
     * @var RadioStationIncomingQueueService
     */
    private $radioStationIncomingQueueService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var StatusServices
     */
    private $statusService;

    /**
     * RadioStationIncomingQueueCreateHandler constructor.
     *
     * @param RadioStationIncomingQueueService $radioStationIncomingQueueService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        RadioStationIncomingQueueService $radioStationIncomingQueueService,
        EventDispatcherInterface $eventDispatcher,
        StatusServices $statusServices)
    {
        $this->radioStationIncomingQueueService = $radioStationIncomingQueueService;
        $this->eventDispatcher = $eventDispatcher;
        $this->statusService = $statusServices;
    }

    /**
     * @param RadioStationQueue $radioStationQueue
     */
    public function approve(RadioStationQueue $radioStationQueue)
    {
        $radioStationQueue->setStatus($this->statusService->active());
        $radioStationQueue->setIsApproved(true);
        $this->radioStationIncomingQueueService->update($radioStationQueue);

        //TOD fire event
    }

}