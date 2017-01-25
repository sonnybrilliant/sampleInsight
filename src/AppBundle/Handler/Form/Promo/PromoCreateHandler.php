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
namespace AppBundle\Handler\Form\Promo;

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Audio\AudioEditorService;
use AppBundle\Service\Promo\PromoService;
use AppBundle\Service\Promo\PromoFileUploaderService;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Song\SongFileUploaderService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class PromoCreateHandler
{
    /**
     * @var PromoService
     */
    private $promoService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SongFileUploaderService
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
     * PromoCreateHandler constructor.
     * @param PromoService $promoService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param PromoFileUploaderService $fileUploaderService
     * @param AudioEditorService $audioEditorService
     * @param ApostleRestFulClientService $apostleClient
     */
    public function __construct(PromoService $promoService, FlashMessageService $alertService, LoggerInterface $logger, PromoFileUploaderService $fileUploaderService, AudioEditorService $audioEditorService, ApostleRestFulClientService $apostleClient)
    {
        $this->promoService = $promoService;
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
        $this->logger->info(FileUtil::getClassName(get_class()).":  promotional content upload started ");

        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $promo = $form->getData();

        try{
            $file = $promo->getLocalFile();
            $arrFile = $this->fileUploaderService->upload($file);
            $promo->setLocalFile($arrFile['fileName']);
            $promo->setRealFilePath($arrFile['dir'].'/'.$arrFile['fileName']);
            $promo->setCode(uniqid('', true));

            // get duration and bit rate
            $arrFileDetails = $this->audioEditorService->getDetails($promo->getRealFilePath());
            $promo->setDuration((int)$arrFileDetails['duration']);
            $promo->setBitrate((int)$arrFileDetails['bit_rate']);
            $promo->setSize(FileUtil::getHumanFileSize($arrFileDetails['size']));

            $this->promoService->create($promo);
            $this->alertService->setSuccess(sprintf("You have successfully added promo: %s ",$promo->getTitle()));

            /**
             * Send task to apostle for queueing
             */

            $response = $this->apostleClient->queueFileUpload(
                ContentType::PROMOTION,
                ApiType::ACRCLOUD,
                $promo->getId()
            );


            if(200 == (int)$response->code)
            {
                $results = json_decode($response->body);
                if((int)$results->status == 200){
                    $this->logger->info(FileUtil::getClassName(get_class()).": successful queue to apostle promotion to bucket processing ");
                }
            }else{
                $this->logger->critical(FileUtil::getClassName(get_class()).": failed queue to apostle promotion to bucket processing ");
            }

        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding promo, please contact admin.");
            $this->logger->critical(FileUtil::getClassName(get_class()).": error occurred message:".$e->getMessage());
            return false;
        }
        return true;
    }

}