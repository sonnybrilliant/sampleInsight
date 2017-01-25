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

use AppBundle\Entity\ContentType;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class ContentTypeService
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
     * ContentTypeService constructor.
     * @param $logger
     * @param $em
     */
    public function __construct(LoggerInterface $logger,EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @return ContentType
     */
    public function music()
    {
        $type = $this->em->getRepository('AppBundle:ContentType')->findOneBy(array('title' => 'music'));

        if(!$type){
          $this->logger->error("Could not find content type music");
        }
        return $type;
    }

    /**
     * @return ContentType
     */
    public function slogan()
    {
        $type = $this->em->getRepository('AppBundle:ContentType')->findOneBy(array('title' =>'slogan'));

        if(!$type){
            $this->logger->error("Could not find content type slogan");
        }
        return $type;
    }

    /**
     * @return ContentType
     */
    public function promo()
    {
        $type = $this->em->getRepository('AppBundle:ContentType')->findOneBy(array('title' =>'promo'));

        if(!$type){
            $this->logger->error("Could not find content type promo");
        }
        return $type;
    }

    /**
     * @return ContentType
     */
    public function advertisement()
    {
        $type = $this->em->getRepository('AppBundle:ContentType')->findOneBy(array('title' =>'advert'));

        if(!$type){
            $this->logger->error("Could not find content type advert");
        }
        return $type;
    }

}