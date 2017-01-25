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

use AppBundle\Service\Artist\ArtistFileUploaderService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Artist\ArtistService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class ArtistCreateHandler
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
     * @var ArtistFileUploaderService
     */
    private $artistFileUploaderService;

    /**
     * ArtistCreateHandler constructor.
     *
     * @param ArtistService $artistService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     */
    public function __construct(
        ArtistService $artistService,
        FlashMessageService $alertService,
        LoggerInterface $logger,
        ArtistFileUploaderService $artistFileUploaderService)
    {
        $this->artistService = $artistService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->artistFileUploaderService = $artistFileUploaderService;

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

        $artist = $form->getData();

        try{
            $file = $artist->getArtistImage();
            if($file){
                $fileName = $this->artistFileUploaderService->upload($file);
                $artist->setArtistImage($fileName);
            }
            $this->artistService->create($artist);
            $this->alertService->setSuccess(sprintf("You have successfully added artist: %s ",$artist->getTitle()));
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding artist, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}