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
namespace AppBundle\EventListener\Song;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Song\SongEvent;
use AppBundle\Event\Song\SongEvents;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use AppBundle\Service\User\UserService;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OnSongUpload implements EventSubscriberInterface
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
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var String
     */
    private $fromName;

    /**
     * @var String
     */
    private $fromEmail;

    /**
     * @var String
     */
    private $siteName;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Router
     */
    private $router;

    /**
     * OnUserCompilerCreate constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param $templating
     * @param $mailer
     * @param $fromName
     * @param $fromEmail
     * @param $siteName
     * @param $userService
     * @param $router
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        $templating,
        $mailer,
        $fromName,
        $fromEmail,
        $siteName,
        UserService $userService,
        Router $router)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->siteName = $siteName;
        $this->userService = $userService;
        $this->router = $router;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            SongEvents::UPLOAD => 'onSongUpload'
        );
    }

    /**
     * @param SongEvent $songEvent
     */
    public function onSongUpload(SongEvent $songEvent)
    {
        $song = $songEvent->getSong();

        if($song){
            $users = $this->getUsers();
            foreach ($users as $user)
            {
                $this->send($this->buildTemplate($user,$song));
            }

        }
    }

    /**
     * @return mixed
     */
    private function getUsers()
    {
        return $this->userService->getUsersWhoCanApproveUploadedSong();
    }

    /**
     * Build email template
     *
     * @param $user
     * @param $song
     * @return array
     */
    private function buildTemplate($user,$song)
    {

        $email = array();
        $email['subject'] = 'A new song has been added, please Approve - '.$song->getTitle().' by '.$song->getArtist()->getTitle();
        $email['fullName'] = ucfirst($user->getFirstName()).' '.ucfirst($user->getLastName());
        $email['title'] = $song->getTitle();
        $email['artist'] = $song->getArtist()->getTitle();
        $email['emailAddress'] = $user->getEmail();
        $email['url'] = $this->router->generate('song_profile',array('slug'=>$song->getSlug()),UrlGeneratorInterface::ABSOLUTE_URL);

        $email['bodyHTML'] = $this->templating->render(
            'email/html/song/on_song_upload.html.twig',
            $email
        );

        $email['bodyTEXT'] = $this->templating->render(
            'email/txt/song/on_song_upload.txt.twig',
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