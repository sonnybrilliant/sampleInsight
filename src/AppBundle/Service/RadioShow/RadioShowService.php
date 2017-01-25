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

use AppBundle\Common\ApiType;
use AppBundle\Common\Async;
use AppBundle\Common\FileUtil;
use AppBundle\Entity\RadioShow;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Handler\RadioShowTimeSlot\RadioShowTimeSlotCreateHandler;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\StatusServices;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\User\UserService;

class RadioShowService
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
     * @var UserService
     */
    private $userService;

    /**
     * @var StatusServices
     */
    private $statusServices;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleClient;

    /**
     * @var RadioShowTimeSlotCreateHandler
     */
    private $radioShowTimeSlotCreateHandler;

    /**
     * RadioShowService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param UserService $userService
     * @param StatusServices $statusServices
     * @param ApostleRestFulClientService $apostleClient
     * @param RadioShowTimeSlotCreateHandler $radioShowTimeSlotCreateHandler
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, UserService $userService, StatusServices $statusServices, ApostleRestFulClientService $apostleClient, RadioShowTimeSlotCreateHandler $radioShowTimeSlotCreateHandler)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->userService = $userService;
        $this->statusServices = $statusServices;
        $this->apostleClient = $apostleClient;
        $this->radioShowTimeSlotCreateHandler = $radioShowTimeSlotCreateHandler;
    }


    /**
     * @param RadioShow $radioShow
     * @return RadioShow
     */
    public function create(RadioShow $radioShow)
    {
        if(!$radioShow->getCreatedBy()){
            $radioShow->setCreatedBy($this->userService->getLoggedInUser());
        }

        $radioShow->setStatus($this->statusServices->active());
        $this->em->persist($radioShow);
        $this->em->flush();

        return $radioShow;
    }

    /**
     * @param RadioShow $radioShow
     * @return RadioShow
     */
    public function update(RadioShow $radioShow)
    {
        $this->em->persist($radioShow);
        $this->em->flush();
        return $radioShow;
    }

    /**
     * @param $id
     * @return RadioShow|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RadioShow')->find($id);
    }

    /**
     * @param $id
     * @param $month
     */
    public function createTimeSlot($id,$month)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": queue request to create time slot");

        /**
         * Fire event to send file for aws storage
         */
        $response = $this->apostleClient->queueAsynchronousProcessingTask(
            Async::RADIO_SHOW_TIME_SLOT,
            ApiType::ASYNC,
            $id,
            $month
        );


        if(200 == (int)$response->code)
        {
            $results = json_decode($response->body);
            if((int)$results->status == 200){
                $this->logger->info(FileUtil::getClassName(get_class()).":  successful queue to apostle ".Async::RADIO_SHOW_TIME_SLOT." for show id:".$id);
            }
        }else{
            $this->logger->critical(FileUtil::getClassName(get_class()).":  failed queue to apostle ".Async::RADIO_SHOW_TIME_SLOT." for show id:".$id);
        }
    }

    /**
     * Create Radio show time slot Asynchronously
     *
     * @param \stdClass $payload
     */
    public function processRequestCreateTimeSlot(\stdClass $payload)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": process request from queue create time slot for showId:".$payload->entityId);

        $radioShow = $this->getById($payload->entityId);

        if($radioShow){
            $this->radioShowTimeSlotCreateHandler->handle($radioShow,$payload);
        }
    }

    public function processShowFromStream(RadioStationStream $radioStationStream)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": process radio show from stream");


    }
}