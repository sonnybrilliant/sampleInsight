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
namespace AppBundle\Handler\Form\Advert;

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Service\Advert\AdvertFileUploaderService;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Audio\AudioEditorService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Advert\AdvertService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;


class AdvertCreateHandler
{
    /**
     * @var AdvertService
     */
    private $advertService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdvertFileUploaderService
     */
    private $advertFileUploaderService;

    /**
     * @var AudioEditorService
     */
    private $audioEditorService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleClient;

    /**
     * AdvertCreateHandler constructor.
     *
     * @param AdvertService $advertService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param AdvertFileUploaderService $advertFileUploaderService
     * @param AudioEditorService $audioEditorService
     * @param ApostleRestFulClientService $apostleClient
     */
    public function __construct(AdvertService $advertService, FlashMessageService $alertService, LoggerInterface $logger, AdvertFileUploaderService $advertFileUploaderService, AudioEditorService $audioEditorService, ApostleRestFulClientService $apostleClient)
    {
        $this->advertService = $advertService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->advertFileUploaderService = $advertFileUploaderService;
        $this->audioEditorService = $audioEditorService;
        $this->apostleClient = $apostleClient;
    }


    /**
     * @param Form $form
     * @return bool
     */
    public function handle(Form $form)
    {
        $this->logger->info(FileUtil::getClassName(get_class()).":  advert content upload started ");

        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $advert = $form->getData();

        try{
            $file = $advert->getLocalFile();
            $arrFile = $this->advertFileUploaderService->upload($file);
            $advert->setLocalFile($arrFile['fileName']);
            $advert->setRealFilePath($arrFile['dir'].'/'.$arrFile['fileName']);
            $advert->setCode(uniqid('', true));

            // get duration and bit rate
            $arrFileDetails = $this->audioEditorService->getDetails($advert->getRealFilePath());
            $advert->setDuration((int)$arrFileDetails['duration']);
            $advert->setBitrate((int)$arrFileDetails['bit_rate']);
            $advert->setSize(FileUtil::getHumanFileSize($arrFileDetails['size']));

            $this->advertService->create($advert);
            $this->alertService->setSuccess(sprintf("You have successfully added advert: %s ",$advert->getTitle()));

            /**
             * Send task to apostle for queueing
             */

            $response = $this->apostleClient->queueFileUpload(
                ContentType::ADVERT,
                ApiType::ACRCLOUD,
                $advert->getId()
            );


            if(200 == (int)$response->code)
            {
                $results = json_decode($response->body);
                if((int)$results->status == 200){
                    $this->logger->info(FileUtil::getClassName(get_class()).":  successful queue to apostle advert to bucket processing ");
                }
            }else{
                $this->logger->critical(FileUtil::getClassName(get_class()).":  failed queue to apostle advert to bucket processing ");
            }
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding advert, please contact admin.");
            $this->logger->error(FileUtil::getClassName(get_class()).": Error occurred, message: ".$e->getMessage());
            return false;
        }
        return true;
    }

}