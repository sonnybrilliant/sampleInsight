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
use Symfony\Bundle\TwigBundle\TwigEngine;
use Swift_Mailer;

class OnUserCompilerCreate implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    private $templating;

    private $mailer;

    private $fromName;

    private $fromEmail;

    private $siteName;

    /**
     * OnUserCompilerCreate constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param $templating
     * @param $mailer
     * @param $fromName
     * @param $fromEmail
     * @param $siteName
     */
    public function __construct(LoggerInterface $logger, EntityManager $em, $templating, $mailer, $fromName, $fromEmail, $siteName)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->siteName = $siteName;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::COMPILER_CREATE => "onUserCompilerCreate",
        );
    }

    /**
     * @param UserEvent $userEvent
     */
    public function onUserCompilerCreate(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();

        if($user){
            $this->send($this->buildTemplate($user));
        }
    }

    /**
     * Build email template.
     *
     * @param Mlanka $user
     *
     * @return array
     */
    private function buildTemplate($user)
    {

        $email = array();
        $email['subject'] = 'Your account has been created on '.$this->siteName;
        $email['fullName'] = ucfirst($user->getFirstName()).' '.ucfirst($user->getLastName());
        $email['password'] = $user->getPlainPassword();
        $email['username'] = $user->getEmail();
        $email['emailAddress'] = $user->getEmail();

        $email['bodyHTML'] = $this->templating->render(
            'email/html/user/on_compiler_create.html.twig',
            $email
        );

        $email['bodyTEXT'] = $this->templating->render(
            'email/txt/user/on_compiler_create.txt.twig',
            $email
        );

        return $email;
    }

    /**
     * Send email.
     *
     * @param array $email
     */
    private function send($email)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject($email['subject'])
            ->setFrom(array($this->fromEmail => $this->fromName))
            ->setTo($email['emailAddress'])
            ->setBody($email['bodyHTML'], 'text/html')
            ->addPart($email['bodyTEXT'], 'text/plain');
        $this->mailer->send($message);
    }
}