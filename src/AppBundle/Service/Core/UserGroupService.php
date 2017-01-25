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

use AppBundle\Entity\UserGroup;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class UserGroupService
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
     * Get Compiler user group
     *
     * @return UserGroup|null|object
     */
    public function compiler()
    {
        $compiler = $this->em->getRepository("AppBundle:UserGroup")->findOneBy(array("title"=>"Compiler"));
        if(!$compiler){
            $this->logger->error("Could not find compiler user group");
        }
        return $compiler;
    }


}