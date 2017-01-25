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
namespace AppBundle\EventListener\Artist;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Artist;
use AppBundle\Event\Artist\ArtistEvent;
use AppBundle\Event\Artist\ArtistEvents;
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
            ArtistEvents::ON_VERIFY => 'onArtistVerify'
        );
    }

    /**
     * @param ArtistEvent $artistEvent
     */
    public function onArtistVerify(ArtistEvent $artistEvent)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": onArtistVerify processing");
        $artist = $artistEvent->getArtist();
        if($artist){
            $count = $this->radioStationStreamService->updateStreamByVerifiedArtist($artist);
            $this->logger->info(FileUtil::getClassName(get_class()) . ": onArtistVerify updated ".$count." records");
        }

        return;
    }
}