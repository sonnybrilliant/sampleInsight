<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */
namespace AppBundle\Service\Core;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Status;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class StatusServices
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
     * StatusServices constructor.
     * @param $logger
     * @param $em
     */
    public function __construct(LoggerInterface $logger,EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @return Status
     */
    public function active()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('active');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Could not find status active");
        }
        return $status;
    }

    /**
     * @return Status
     */
    public function pending()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('pending');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ":Could not find status active");
        }
        return $status;
    }

    /**
     * @return Status
     */
    public function rejected()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('rejected');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Could not find status active");
        }
        return $status;
    }

    /**
     * @return Status
     */
    public function ready()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('ready');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Could not find status active");
        }
        return $status;
    }

    /**
     * @return Status
     */
    public function error()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('error');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ":Could not find status error");
        }
        return $status;
    }

    /**
     * @return Status
     */
    public function notVerified()
    {
        $status = $this->em->getRepository('AppBundle:Status')->getByCode('notverified');

        if(!$status){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Could not find status notverified");
        }
        return $status;
    }


}