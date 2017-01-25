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
namespace AppBundle\Service\Slogan;

use AppBundle\Entity\Slogan;
use AppBundle\Service\Core\ContentTypeService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;
use AppBundle\Service\RadioStation\RadioStationStreamService;

class SloganService
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
     * @var ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var RadioStationStreamService
     */
    private $radioStationStreamService;

    /**
     * SloganService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param UserService $userService
     * @param ContentTypeService $contentTypeService
     * @param RadioStationStreamService $radioStationStreamService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $status, UserService $userService, ContentTypeService $contentTypeService, RadioStationStreamService $radioStationStreamService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
        $this->contentTypeService = $contentTypeService;
        $this->radioStationStreamService = $radioStationStreamService;
    }

    /**
     * @param Slogan $slogan
     * @return Slogan
     */
    public function create(Slogan $slogan)
    {
        if(!$slogan->getCreatedBy()){
            $slogan->setCreatedBy($this->userService->getLoggedInUser());
        }

        $slogan->setStatus($this->status->pending());
        $slogan->setContentType($this->contentTypeService->slogan());
        $this->em->persist($slogan);
        $this->em->flush();

        return $slogan;
    }

    /**
     * @param $id
     * @return Slogan|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Slogan')->find($id);
    }

    /**
     * Get Slogan by Code
     *
     * @param $code
     * @return Slogan|null|object
     */
    public function getByCode($code)
    {
        return $this->em->getRepository("AppBundle:Slogan")->findOneBy(array(
            'code' => $code
        ));
    }

    /**
     * @param Slogan $slogan
     * @return Slogan
     */
    public function update(Slogan $slogan)
    {
        $this->em->persist($slogan);
        $this->em->flush();
        return $slogan;
    }

}