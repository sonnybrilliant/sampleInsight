<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/02
 */
namespace AppBundle\Service\RadioStation;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\RadioStationQueue;
use Psr\Log\LoggerInterface;

class RadioStationIncomingQueueService
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
     * RadioStationService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     */
    public function __construct(LoggerInterface $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @param $id
     * @return RadioStationQueue|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RadioStationQueue')->find($id);
    }

    /**
     * @param RadioStationQueue $radioStationQueue
     */
    public function create(RadioStationQueue $radioStationQueue)
    {
        $this->em->persist($radioStationQueue);
        $this->em->flush();
    }

    /**
     * @param RadioStationQueue $radioStationQueue
     */
    public function update(RadioStationQueue $radioStationQueue)
    {
        $this->em->persist($radioStationQueue);
        $this->em->flush();
    }

}