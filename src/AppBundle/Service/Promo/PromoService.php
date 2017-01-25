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
namespace AppBundle\Service\Promo;

use AppBundle\Entity\Promo;
use AppBundle\Service\Core\ContentTypeService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\User\UserService;

class PromoService
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
     * PromoService constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param StatusServices $status
     * @param UserService $userService
     * @param ContentTypeService $contentTypeService
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, StatusServices $status, UserService $userService, ContentTypeService $contentTypeService)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->status = $status;
        $this->userService = $userService;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Create Promo
     * @param Promo $promo
     * @return Promo
     */
    public function create(Promo $promo)
    {
        if(!$promo->getCreatedBy()){
            $promo->setCreatedBy($this->userService->getLoggedInUser());
        }

        $promo->setStatus($this->status->pending());
        $promo->setContentType($this->contentTypeService->slogan());
        $this->em->persist($promo);
        $this->em->flush();

        return $promo;
    }

    /**
     * @param $id
     * @return Promo |null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:Promo')->find($id);
    }

    /**
     * Get Promo by Code
     *
     * @param $code
     * @return Promo|null|object
     */
    public function getByCode($code)
    {
        return $this->em->getRepository("AppBundle:Promo")->findOneBy(array(
            'code' => $code
        ));
    }

    /**
     * @param Promo $promo
     * @return Promo
     */
    public function update(Promo $promo)
    {
        $this->em->persist($promo);
        $this->em->flush();
        return $promo;
    }

}