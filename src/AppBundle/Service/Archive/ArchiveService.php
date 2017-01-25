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
namespace AppBundle\Service\Archive;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Archive;
use AppBundle\Entity\RadioStationStream;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;

class ArchiveService
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
     * @var String
     *
     * folder name is different when you get path via command line
     */
    private $strRecordingsFolder;

    /**
     * ArchiveService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param String $strRecordingsFolder
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $status, $strRecordingsFolder)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->strRecordingsFolder = $strRecordingsFolder;
    }


    /**
     * @param Archive $archive
     * @return Archive
     */
    public function create(Archive $archive)
    {
        $archive->setStatus($this->status->pending());
        $this->em->persist($archive);
        $this->em->flush();
        return $archive;
    }

    /**
     * @param $id
     * @return Archive|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Archive')->find($id);
    }

    /**
     * @param Archive $archive
     * @return Archive
     */
    public function update(Archive $archive)
    {
        $this->em->persist($archive);
        $this->em->flush();
        return $archive;
    }

    /**
     * @return String
     */
    public function getRecordingsPath()
    {
        return $this->strRecordingsFolder;
    }
}