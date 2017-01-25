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
namespace AppBundle\Service\RecordLabel;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Entity\RecordLabel;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;

class RecordLabelService
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
     * UserService constructor.
     * @param $logger
     * @param $em
     * @param $status
     * @param $userService
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        StatusServices $status,
        UserService $userService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
    }

    /**
     * @param $id
     * @return RecordLabel|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RecordLabel')->find($id);
    }

    /**
     * @param RecordLabel $recordLabel
     * @return RecordLabel
     */
    public function create(RecordLabel $recordLabel)
    {
        if(!$recordLabel->getCreatedBy()){
            $recordLabel->setCreatedBy($this->userService->getLoggedInUser());
        }

        $recordLabel->setStatus($this->status->active());
        $this->em->persist($recordLabel);
        $this->em->flush();
        return $recordLabel;
    }

    /**
     * @param RecordLabel $recordLabel
     * @return RecordLabel
     */
    public function update(RecordLabel $recordLabel)
    {
        $this->em->persist($recordLabel);
        $this->em->flush();
        return $recordLabel;
    }

    /**
     * Process incoming radio station stream
     *
     * @param RadioStationStream $radioStationStream
     * @return bool
     */
    public function processRecordLabelFromStream(RadioStationStream $radioStationStream)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": Process record label from live stream");

        //check if name is not blank
        if((count($radioStationStream->getLabel()) > 2) ){
            $this->logger->info(FileUtil::getClassName(get_class()) . ": record label is not empty");
            $recordLabelName = $radioStationStream->getLabel();
            $result = $this->em->getRepository("AppBundle:RecordLabel")->findOneBy(array('hiddenName' => $recordLabelName));
            //if name exist
            $this->logger->info(FileUtil::getClassName(get_class()) . ": search for record label by name:".$recordLabelName);
            if(!$result){
               $result = $this->em->getRepository("AppBundle:RecordLabel")->findOneBy(array('name' => $recordLabelName));
            }

            if($result){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": record label found by name:".$recordLabelName);
                //check if record label is verified
                if($result->getStatus()->getId() != $this->status->notVerified()){
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": record label status is not equal to 'Not Verified' :".$recordLabelName);

                    if("" == $result->getHiddenName()){
                        $result->setHiddenName($recordLabelName);
                        $this->em->persist($result);
                    }

                    //Update stream
                    $radioStationStream->setRecordLabel($result);
                    $this->em->persist($radioStationStream);

                    $this->em->flush();
                }
            }else{
                $this->logger->info(FileUtil::getClassName(get_class()) . ": record label does not exist, create one name:".$recordLabelName);

                //create record label
                $recordLabel = new RecordLabel();
                $recordLabel->setName($recordLabelName);
                $recordLabel->setRegisteredAs($recordLabelName);
                $recordLabel->setHiddenName($recordLabelName);
                $recordLabel->setStatus($this->status->notVerified());
                $recordLabel->setIsLocal(false);
                $this->em->persist($recordLabel);
                $this->em->flush();

                //update radio station stream
                $radioStationStream->setRecordLabel($recordLabel);
                $this->em->persist($radioStationStream);
                $this->em->flush();
                $this->logger->info(FileUtil::getClassName(get_class()) . ": record label created, process done for:".$recordLabelName);

            }
        }

        return true;
    }

}