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
namespace AppBundle\EventListener\User;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\UserEvent;
use AppBundle\Event\UserEvents;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class OnUserLogin implements EventSubscriberInterface
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
     * OnUserLogin constructor.
     *
     * @param $logger
     * @param $em
     */
    public function __construct(LoggerInterface $logger,EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::LAST_LOGIN => "onUserLogin",
        );
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onUserLogin(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();
        if($user){
            $user->setLastLoginAt(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();
        }
    }

}