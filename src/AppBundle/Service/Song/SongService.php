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
namespace AppBundle\Service\Song;

use AppBundle\Entity\Song;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;
use AppBundle\Service\RadioStation\RadioStationStreamService;

class SongService
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
     * @var StatusServices
     */
    private $status;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RadioStationStreamService
     */
    private $radioStationStreamService;

    /**
     * SongService constructor.
     *
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param UserService $userService
     * @param RadioStationStreamService $radioStationStreamService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $status, UserService $userService, RadioStationStreamService $radioStationStreamService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
        $this->radioStationStreamService = $radioStationStreamService;
    }

    /**
     * @param Song $song
     * @return Song
     */
    public function create(Song $song)
    {
        if(!$song->getCreatedBy()){
            $song->setCreatedBy($this->userService->getLoggedInUser());
        }

        $song->setStatus($this->status->pending());
        $this->em->persist($song);
        $this->em->flush();

        /**
         * TODO move to queue
         */
        if($song->getIsrc()){
            $this->radioStationStreamService->updateStreamsWithSongDetails($song);
        }

        return $song;
    }

    /**
     * @param $id
     * @return Song|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Song')->find($id);
    }

    /**
     * Get Song by ISRC
     *
     * @param $isrc
     * @return Song|null|object
     */
    public function getByISRC($isrc)
    {
        return $this->em->getRepository("AppBundle:Song")->findOneBy(array(
            'isrc' => $isrc
        ));
    }

    /**
     * @param Song $song
     * @return Song
     */
    public function update(Song $song)
    {
        $this->em->persist($song);
        $this->em->flush();
        return $song;
    }

    /**
     * Approve song
     *
     * @param Song $song
     * @return Song
     */
    public function approveUploadedSong(Song $song)
    {
        $song->setStatus($this->status->active());
        $song->setApprovedAt(new \DateTime());
        $song->setApprovedBy($this->userService->getLoggedInUser());

        //Fire event
        return $this->update($song);
    }

}