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
namespace AppBundle\Handler\Form\Artist;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Status;
use AppBundle\Event\Artist\ArtistEvent;
use AppBundle\Event\Artist\ArtistEvents;
use AppBundle\Service\Artist\ArtistFileUploaderService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Artist\ArtistService;
use AppBundle\Service\Core\StatusServices;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class ArtistEditHandler
{
    /**
     * @var ArtistService
     */
    private $artistService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StatusServices
     */
    private $statusService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ArtistFileUploaderService
     */
    private $artistFileUploaderService;

    /**
     * ArtistEditHandler constructor.
     * @param ArtistService $artistService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param StatusServices $statusService
     * @param EventDispatcherInterface $eventDispatcher
     * @param ArtistFileUploaderService $artistFileUploaderService
     */
    public function __construct(ArtistService $artistService, FlashMessageService $alertService, LoggerInterface $logger, StatusServices $statusService, EventDispatcherInterface $eventDispatcher, ArtistFileUploaderService $artistFileUploaderService)
    {
        $this->artistService = $artistService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->statusService = $statusService;
        $this->eventDispatcher = $eventDispatcher;
        $this->artistFileUploaderService = $artistFileUploaderService;
    }

    /**
     * @param Form $form
     * @param Status $currentStatus
     * @param $currentImage
     * @return bool
     */
    public function handle(Form $form, Status $currentStatus, $currentImage)
    {
        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $artist = $form->getData();

        try{

            $file = $artist->getArtistImage();

            if($file){
                $fileName = $this->artistFileUploaderService->upload($file);
                $artist->setArtistImage($fileName);
            }else{
                $artist->setArtistImage($currentImage);
            }
            $this->artistService->update($artist);
            $this->alertService->setSuccess(sprintf("You have successfully update artist: %s ",$artist->getTitle()));

            //check status
            if($currentStatus->getCode() != $artist->getStatus()->getCode()){
                if(($this->statusService->notVerified()->getCode() == $currentStatus->getCode()) && ($artist->getStatus()->getCode() == $this->statusService->active()->getCode())){
                    //check if old status was 'Not verified' and The new status is 'Active'
                    //Trigger event
                    $this->eventDispatcher->dispatch(
                        ArtistEvents::ON_VERIFY,
                        new ArtistEvent($artist)
                    );
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": trigger event :".ArtistEvents::ON_VERIFY);
                }
            }
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding artist, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}