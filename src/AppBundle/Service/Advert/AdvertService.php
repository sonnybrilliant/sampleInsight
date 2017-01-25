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
namespace AppBundle\Service\Advert;

use AppBundle\Entity\Advert;
use AppBundle\Service\Core\ContentTypeService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;
use AppBundle\Service\RadioStation\RadioStationStreamService;

class AdvertService
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
     * Advertervice constructor.
     *
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param UserService $userService
     * @param ContentTypeService $contentTypeService
     * @param RadioStationStreamService $radioStationStreamService
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        StatusServices $status,
        UserService $userService,
        ContentTypeService $contentTypeService,
        RadioStationStreamService $radioStationStreamService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
        $this->contentTypeService = $contentTypeService;
        $this->radioStationStreamService = $radioStationStreamService;
    }

    /**
     * @param Advert $advert
     * @return Advert
     */
    public function create(Advert $advert)
    {
        if(!$advert->getCreatedBy()){
            $advert->setCreatedBy($this->userService->getLoggedInUser());
        }

        $advert->setStatus($this->status->pending());
        $advert->setContentType($this->contentTypeService->advertisement());
        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
    }

    /**
     * @param $id
     * @return Advert|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Advert')->find($id);
    }

    /**
     * Get Advert by Code
     *
     * @param $code
     * @return Advert|null|object
     */
    public function getByCode($code)
    {
        return $this->em->getRepository("AppBundle:Advert")->findOneBy(array(
            'code' => $code
        ));
    }

    /**
     * @param Advert $advert
     * @return Advert
     */
    public function update(Advert $advert)
    {
        $this->em->persist($advert);
        $this->em->flush();
        return $advert;
    }

}