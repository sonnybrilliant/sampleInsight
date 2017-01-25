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
namespace AppBundle\Handler\Form\Slogan;

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Audio\AudioEditorService;
use AppBundle\Service\Slogan\SloganFileUploaderService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Slogan\SloganService;
use AppBundle\Service\Song\SongFileUploaderService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class SloganCreateHandler
{
    /**
     * @var SloganService
     */
    private $sloganService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SloganFileUploaderService
     */
    private $fileUploaderService;

    /**
     * @var AudioEditorService
     */
    private $audioEditorService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleClient;

    /**
     * SloganCreateHandler constructor.
     * @param SloganService $sloganService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param SloganFileUploaderService $fileUploaderService
     * @param AudioEditorService $audioEditorService
     * @param ApostleRestFulClientService $apostleClient
     */
    public function __construct(SloganService $sloganService, FlashMessageService $alertService, LoggerInterface $logger, SloganFileUploaderService $fileUploaderService, AudioEditorService $audioEditorService, ApostleRestFulClientService $apostleClient)
    {
        $this->sloganService = $sloganService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->fileUploaderService = $fileUploaderService;
        $this->audioEditorService = $audioEditorService;
        $this->apostleClient = $apostleClient;
    }


    /**
     * @param Form $form
     * @return bool
     */
    public function handle(Form $form)
    {
        $this->logger->info(FileUtil::getClassName(get_class()).": slogan content upload started ");
        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $slogan = $form->getData();

        try{
            $file = $slogan->getLocalFile();
            $arrFile = $this->fileUploaderService->upload($file);
            $slogan->setLocalFile($arrFile['fileName']);
            $slogan->setRealFilePath($arrFile['dir'].'/'.$arrFile['fileName']);
            $slogan->setCode(uniqid('', true));

            // get duration and bit rate
            $arrFileDetails = $this->audioEditorService->getDetails($slogan->getRealFilePath());
            $slogan->setDuration((int)$arrFileDetails['duration']);
            $slogan->setBitrate((int)$arrFileDetails['bit_rate']);
            $slogan->setSize(FileUtil::getHumanFileSize($arrFileDetails['size']));

            $this->sloganService->create($slogan);
            $this->alertService->setSuccess(sprintf("You have successfully added slogan: %s ",$slogan->getTitle()));

            /**
             * Send task to apostle for queueing
             */

            $response = $this->apostleClient->queueFileUpload(
                ContentType::SLOGAN,
                ApiType::ACRCLOUD,
                $slogan->getId()
            );


            if(200 == (int)$response->code)
            {
                $results = json_decode($response->body);
                if((int)$results->status == 200){
                    $this->logger->info(FileUtil::getClassName(get_class()).":  successful queue to apostle slogan to bucket processing ");
                }
            }else{
                $this->logger->critical(FileUtil::getClassName(get_class()).":  failed queue to apostle slogan to bucket processing ");
            }

        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding slogan, please contact admin.");
            $this->logger->error(FileUtil::getClassName(get_class()).": Error occurred, message: ".$e->getMessage());
            return false;
        }
        return true;
    }

}