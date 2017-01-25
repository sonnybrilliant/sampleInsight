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
namespace AppBundle\Service\RadioShow;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\RadioShow;
use AppBundle\Entity\RadioShowTimeSlot;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Handler\RadioShowTimeSlot\RadioShowTimeSlotCreateHandler;
use AppBundle\Service\Core\StatusServices;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\User\UserService;

class RadioShowTimeSlotService
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
    private $statusServices;

    /**
     * RadioShowTimeSlotService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $statusServices
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $statusServices)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->statusServices = $statusServices;
    }


    /**
     * @param RadioShowTimeSlot $radioShowTimeSlot
     * @return RadioShowTimeSlot
     */
    public function create(RadioShowTimeSlot $radioShowTimeSlot)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": create radio show time slot");

        $radioShowTimeSlot->setStatus($this->statusServices->active());
        $this->em->persist($radioShowTimeSlot);
        $this->em->flush();

        return $radioShowTimeSlot;
    }

}