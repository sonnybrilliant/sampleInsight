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

class RadioStationIncomingQueueCreateHandler
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
     * @param $song
     */
    public function add(Song $song)
    {
        $radioStations = $song->getTargetedRadioStations();

        if($radioStations){
            foreach ($radioStations as $radioStation){
                $queue = new RadioStationQueue();
                $queue->setSong($song);
                $queue->setStatus($this->statusService->pending());
                $queue->setArtist($song->getArtist());
                $queue->setRadioStation($radioStation);

                if($song->getRecordLabel()){
                    $queue->setRecordLabel($song->getRecordLabel());
                }

                $this->radioStationIncomingQueueService->create($queue);

            }

            /**
             * Fire event and let users with approve roles know
             */
            $this->eventDispatcher->dispatch(
                SongEvents::UPLOAD_APPROVE,
                new SongEvent($song)
            );
        }
    }

}