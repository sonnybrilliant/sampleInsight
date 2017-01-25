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

use AppBundle\Entity\Song;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Song\SongEvent;
use AppBundle\Event\Song\SongEvents;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use AppBundle\Service\User\CompilerService;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OnSongInitialApprove implements EventSubscriberInterface
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
     * @var CompilerService
     */
    private $compilerService;

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
     * @param $compilerService
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
        CompilerService $compilerService,
        Router $router)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->siteName = $siteName;
        $this->compilerService = $compilerService;
        $this->router = $router;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            SongEvents::UPLOAD_APPROVE => 'onSongUploadApprove'
        );
    }

    /**
     * @param SongEvent $songEvent
     */
    public function onSongUploadApprove(SongEvent $songEvent)
    {
        $song = $songEvent->getSong();

        if($song){
            $radioStations = $song->getTargetedRadioStations();

            foreach ($radioStations as $radioStation)
            {
                $users = $this->getUsersByRadioStation($radioStation);
                foreach ($users as $user)
                {
                    $this->send($this->buildTemplate($user,$song));
                }
            }

        }
    }

    /**
     * @return mixed
     */
    private function getUsersByRadioStation($radioStation)
    {
        return $this->compilerService->getCompilersByRadioStation($radioStation);
    }

    /**
     * Build email template
     *
     * @param $user
     * @param $song
     * @return array
     */
    private function buildTemplate(User $user,Song $song)
    {

        $email = array();
        $email['subject'] = 'A new song has been added, please Approve - '.$song->getTitle().' by '.$song->getArtist()->getTitle();
        $email['fullName'] = ucfirst($user->getFirstName()).' '.ucfirst($user->getLastName());
        $email['title'] = $song->getTitle();
        $email['artist'] = $song->getArtist()->getTitle();
        $email['radio'] = $user->getRadioStation()->getName();
        $email['emailAddress'] = $user->getEmail();
        $email['url'] = $this->router->generate('radio_station_incoming_list',array(),UrlGeneratorInterface::ABSOLUTE_URL);

        $email['bodyHTML'] = $this->templating->render(
            'email/html/song/on_song_upload_compiler_approve.html.twig',
            $email
        );

        $email['bodyTEXT'] = $this->templating->render(
            'email/txt/song/on_song_upload_compiler_approve.txt.twig',
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