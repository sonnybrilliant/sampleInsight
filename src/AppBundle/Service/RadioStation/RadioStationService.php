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
use AppBundle\Entity\RadioStation;
use Psr\Log\LoggerInterface;

class RadioStationService
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
     * @return RadioStation|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RadioStation')->find($id);
    }

    /**
     * @param $streamId
     * @return RadioStation|null|object
     */
    public function getByStreamId($streamId)
    {
        return $this->em->getRepository('AppBundle:RadioStation')->findOneBy(array('streamId'=>$streamId));
    }

    /**
     * @param RadioStation $radioStation
     */
    public function create(RadioStation $radioStation)
    {
        $this->em->persist($radioStation);
        $this->em->flush();
    }
}