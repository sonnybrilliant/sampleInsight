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
namespace AppBundle\Handler\Form\Song;

use AppBundle\Service\Song\SongFileUploaderService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Song\SongService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;
use AppBundle\Event\Song\SongEvent;
use AppBundle\Event\Song\SongEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SongCreateHandler
{
    /**
     * @var SongService
     */
    private $songService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $songFileUploaderService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * SongCreateHandler constructor.
     * @param SongService $songService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param SongFileUploaderService $songFileUploaderService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        SongService $songService,
        FlashMessageService $alertService,
        LoggerInterface $logger,
        SongFileUploaderService $songFileUploaderService,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->songService = $songService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->songFileUploaderService = $songFileUploaderService;
        $this->eventDispatcher = $eventDispatcher;

    }

    /**
     * @param Form $form
     * @return bool
     */
    public function handle(Form $form)
    {
        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $song = $form->getData();

        try{
            $file = $song->getLocalFile();
            $fileName = $this->songFileUploaderService->upload($file);
            $song->setLocalFile($fileName);
            $this->songService->create($song);
            $this->alertService->setSuccess(sprintf("You have successfully added song: %s ",$song->getTitle()));

            /**
             * Fire event and let users with approve roles know
             */
//            $this->eventDispatcher->dispatch(
//                SongEvents::UPLOAD,
//                new SongEvent($song)
//            );
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding song, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}