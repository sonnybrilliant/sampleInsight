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
namespace AppBundle\EventListener\RecordLabel;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\RecordLabel;
use AppBundle\Event\RecordLabel\RecordLabelEvent;
use AppBundle\Event\RecordLabel\RecordLabelEvents;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class OnVerify implements EventSubscriberInterface
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
     * OnVerify constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param RadioStationStreamService $radioStationStreamService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, RadioStationStreamService $radioStationStreamService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->radioStationStreamService = $radioStationStreamService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            RecordLabelEvents::ON_VERIFY => 'onRecordLabelVerify'
        );
    }

    /**
     * Update
     * @param RecordLabelEvent $recordLabelEvent
     */
    public function onRecordLabelVerify(RecordLabelEvent $recordLabelEvent)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": onRecordLabelVerify processing");
        $recordLabel = $recordLabelEvent->getRecordLabel();
        if($recordLabel){
            $count = $this->radioStationStreamService->updateStreamByVerifiedRecordLabel($recordLabel);
            $this->logger->info(FileUtil::getClassName(get_class()) . ": onRecordLabelVerify updated ".$count." records");

        }
        return;
    }
}