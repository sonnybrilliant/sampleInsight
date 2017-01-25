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
namespace AppBundle\EventListener\RadioStationStream;

use AppBundle\Common\FileUtil;
use AppBundle\Common\StreamProcessType;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvent;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvents;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class OnStreamIncoming implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RadioStationStreamService
     */
    private $radioStationStreamService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleRestFulClientService;

    /**
     * OnStreamIncoming constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param RadioStationStreamService $radioStationStreamService
     * @param ApostleRestFulClientService $apostleRestFulClientService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, RadioStationStreamService $radioStationStreamService, ApostleRestFulClientService $apostleRestFulClientService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->radioStationStreamService = $radioStationStreamService;
        $this->apostleRestFulClientService = $apostleRestFulClientService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            RadioStationStreamEvents::ON_STREAM_INCOMING => 'onRecordLabelCheck',
            RadioStationStreamEvents::ON_STREAM_INCOMING => 'onArtistCheck',
            RadioStationStreamEvents::ON_STREAM_CHECK_SHOW => 'onShowCheck'
        );
    }

    /**
     * On record label process
     *
     * @param RadioStationStreamEvent $radioStationStreamEvent
     */
    public function onRecordLabelCheck(RadioStationStreamEvent $radioStationStreamEvent)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": onRecordLabelCheck processing");
        $stream = $radioStationStreamEvent->getRadioStationStream();

        if($stream){
            //check record label id
            if(!$stream->getRecordLabel()){
                $response = $this->apostleRestFulClientService->queueStreamProcessingTask(
                    $stream->getId(),
                    StreamProcessType::RECORD_LABEL
                );

                if (200 == (int)$response->code) {
                    $results = json_decode($response->body);
                    if ((int)$results->status == 200) {
                        $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully posted stream processing:".StreamProcessType::RECORD_LABEL);
                    }
                } else {
                    $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to post stream processing:".StreamProcessType::RECORD_LABEL);
                }
            }else{
                $this->logger->info(FileUtil::getClassName(get_class()) . ": processing done, record label already exists");
            }
        }
        return;
    }

    /**
     * @param RadioStationStreamEvent $radioStationStreamEvent
     */
    public function onArtistCheck(RadioStationStreamEvent $radioStationStreamEvent)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": onArtistCheck processing");
        $stream = $radioStationStreamEvent->getRadioStationStream();

        if($stream){
            //check artist id
            if(!$stream->getArtistObject()){
                $response = $this->apostleRestFulClientService->queueStreamProcessingTask(
                    $stream->getId(),
                    StreamProcessType::ARTIST
                );

                if (200 == (int)$response->code) {
                    $results = json_decode($response->body);
                    if ((int)$results->status == 200) {
                        $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully posted stream processing:".StreamProcessType::ARTIST);
                    }
                } else {
                    $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to post stream processing:".StreamProcessType::ARTIST);
                }
            }else{
                $this->logger->info(FileUtil::getClassName(get_class()) . ": processing done, artist already exists");
            }
        }
        return;
    }

    /**
     * @param RadioStationStreamEvent $radioStationStreamEvent
     */
    public function onShowCheck(RadioStationStreamEvent $radioStationStreamEvent)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": onShowCheck processing");
        $stream = $radioStationStreamEvent->getRadioStationStream();

        if($stream){
            $response = $this->apostleRestFulClientService->queueStreamProcessingTask(
                $stream->getId(),
                StreamProcessType::SHOW
            );

            if (200 == (int)$response->code) {
                $results = json_decode($response->body);
                if ((int)$results->status == 200) {
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully posted stream processing:".StreamProcessType::SHOW);
                }
            } else {
                $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to post stream processing:".StreamProcessType::SHOW);
            }
        }
        return;
    }
}