<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/08
 */
namespace AppBundle\Consumer\Audio;

use AppBundle\Common\ApiType;
use AppBundle\Common\Audio;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Service\Advert\AdvertService;
use AppBundle\Service\Core\Acrcloud\AcrcloudUploadAudioService;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\Promo\PromoService;
use AppBundle\Service\Slogan\SloganService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;


class UploadFileFingerPrintBucketConsumer implements ConsumerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StatusServices
     */
    private $statusService;

    /**
     * @var SloganService
     */
    private $sloganService;

    /**
     * @var AdvertService
     */
    private $advertService;

    /**
     * @var PromoService
     */
    private $promoService;

    /**
     * @var AcrcloudUploadAudioService
     */
    private $acrcloudUploadAudioService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleRestFulClientService;

    /**
     * UploadFileFingerPrintBucketConsumer constructor.
     * @param LoggerInterface $logger
     * @param StatusServices $statusService
     * @param SloganService $sloganService
     * @param AdvertService $advertService
     * @param PromoService $promoService
     * @param AcrcloudUploadAudioService $acrcloudUploadAudioService
     * @param ApostleRestFulClientService $apostleRestFulClientService
     */
    public function __construct(LoggerInterface $logger, StatusServices $statusService, SloganService $sloganService, AdvertService $advertService, PromoService $promoService, AcrcloudUploadAudioService $acrcloudUploadAudioService, ApostleRestFulClientService $apostleRestFulClientService)
    {
        $this->logger = $logger;
        $this->statusService = $statusService;
        $this->sloganService = $sloganService;
        $this->advertService = $advertService;
        $this->promoService = $promoService;
        $this->acrcloudUploadAudioService = $acrcloudUploadAudioService;
        $this->apostleRestFulClientService = $apostleRestFulClientService;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     * @throws \Exception
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": acrcloud incoming rabbitmq message");
        $payload = json_decode($msg->body);

        try {
            $this->process($payload);
        } catch (\Exception $e) {
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Failed to upload file for finger printing error:" . $e->getMessage());
        }
        return true;
    }

    /**
     * Process payload
     *
     * @param $payload
     */
    private function process($payload)
    {
        //check Audio file type Slogan, Advert, Promo
        $audio = null;
        $contentType = null;

        /**
         * Check content type e.g Slogan / Advert / Promotional
         */
        if ($payload->contentType == ContentType::SLOGAN) {
            $audio = $this->sloganService->getById($payload->fileId);
            $contentType = ContentType::SLOGAN;
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content type:" . ContentType::SLOGAN);
        } elseif ($payload->contentType == ContentType::ADVERT) {
            $audio = $this->advertService->getById($payload->fileId);
            $contentType = ContentType::ADVERT;
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content type:" . ContentType::ADVERT);
        } elseif ($payload->contentType == ContentType::PROMOTION) {
            $audio = $this->promoService->getById($payload->fileId);
            $contentType = ContentType::PROMOTION;
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content type:" . ContentType::PROMOTION);
        }


        /**
         * Once content type is established, process request to upload content
         */
        if ($audio) {
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content is valid, ready to upload");
            $arrParam = array();
            if ($contentType == ContentType::PROMOTION) {
                $arrParam = $this->getPromotionalParameters($audio);
            } elseif ($contentType == ContentType::SLOGAN) {
                $arrParam = $this->getSloganParameters($audio);
            } elseif ($contentType == ContentType::ADVERT){
                $arrParam = $this->getAdvertParameters($audio);
            }

            /**
             * Upload file
             */
            $this->processContent($audio, $contentType, $payload, $arrParam);
        } else {
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": could not find content:".$contentType." by Id:" . $payload->fileId);
        }
    }

    /**
     * Process content
     *
     * @param Audio $audio
     * @param $contentType
     * @param $payload
     * @param $arrParam
     */
    private function processContent(Audio $audio, $contentType, $payload, $arrParam)
    {

        /**
         * Upload file
         */
        try {
            $this->logger->info(FileUtil::getClassName(get_class()) . ": uploading content type:" . $contentType . " Id:" . $payload->fileId);
            $str = $this->acrcloudUploadAudioService->uploadContent($arrParam);
            $response = \json_decode($str);

            //Check if response was successful
            if (isset($response->acr_id)) {
                /**
                 * Successful Post
                 * - Update content
                 */
                $audio->setIsUploadedToBucket(true);
                $audio->setArcloudId($response->acr_id);
                $this->updateSuccessfulPost($audio, $contentType);
                $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully uploaded content type:" . $contentType . " Id:" . $payload->fileId);
            } else {
                /**
                 * Failed to post
                 * - Update content
                 */
                $audio->setError($str);
                $this->updateFailedPost($audio, $contentType, $payload->correlationId, $str);
                $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed uploading content type:" . $contentType . " Id:" . $payload->fileId . " error:" . $str);
            }

        } catch (\Exception $e) {
            $audio->setError($e->getMessage());
            $this->updateFailedPost($audio, $contentType, $payload->correlationId, $e->getMessage());
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed uploading content type:" . $contentType . " Id:" . $payload->fileId . " error:" . $e->getMessage());
        }
    }

    /**
     * Get promotional content headers
     *
     * @param $audio
     * @return array
     */
    private function getPromotionalParameters($audio)
    {
        return array(
            'file' => $audio->getRealFilePath(),
            'radio_station_id' => $audio->getRadioStation()->getId(),
            'radio_station_name' => $audio->getRadioStation()->getName(),
            'is_show_promotion' => $audio->getIsRadioPromo(),
            'show_id' => $audio->getRadioShow() ? $audio->getRadioShow()->getId() : null,
            'show_name' => $audio->getIsRadioPromo() ? $audio->getRadioShow()->getTitle() : null,
            'code' => $audio->getCode(),
            'title' => $audio->getTitle(),
            'type' => ContentType::PROMOTION,
            'artist' => $audio->getIsRadioPromo() ? $audio->getRadioShow()->getTitle() : $audio->getRadioStation()->getName()
        );
    }

    /**
     * Get slogan content headers
     *
     * @param $audio
     * @return array
     */
    private function getSloganParameters($audio)
    {
        return array(
            'file' => $audio->getRealFilePath(),
            'radio_station_id' => $audio->getRadioStation()->getId(),
            'radio_station_name' => $audio->getRadioStation()->getName(),
            'code' => $audio->getCode(),
            'title' => $audio->getTitle(),
            'type' => ContentType::SLOGAN,
            'artist' => $audio->getRadioStation()->getName()
        );
    }

    /**
     * Get advert content headers
     *
     * @param $audio
     * @return array
     */
    private function getAdvertParameters($audio)
    {
        return array(
            'file' => $audio->getRealFilePath(),
            'advert_organization_id' => $audio->getAdvertisingOrganization()->getId(),
            'advert_organization_name' => $audio->getAdvertisingOrganization()->getName(),
            'code' => $audio->getCode(),
            'expiry_date' => $audio->getExpireAt()->format('Y-m-d'),
            'title' => $audio->getTitle(),
            'type' => ContentType::ADVERT,
            'artist' => $audio->getAdvertisingOrganization()->getName()
        );
    }

    /**
     * Update successful status
     *
     * @param Audio $audio
     * @param $contentType
     * @return bool
     */
    private function updateSuccessfulPost(Audio $audio, $contentType)
    {
        $audio->setUploadedToBucketAt(new \DateTime());
        $canDeleteFile = false;
        /**
         * Check if AWS is done
         */
        if ($audio->getIsUploadedToS3() && $audio->getIsUploadedToBucket()) {
            $audio->setStatus($this->statusService->active());
            $canDeleteFile = true;
        }

        if ($contentType == ContentType::PROMOTION) {
            $this->promoService->update($audio);
        } elseif ($contentType == ContentType::SLOGAN) {
            $this->sloganService->update($audio);
        } elseif ($contentType == ContentType::ADVERT){
            $this->advertService->update($audio);
        }

        /**
         * Fire event to send file for aws storage
         */
        $response = $this->apostleRestFulClientService->queueFileUpload(
            $contentType,
            ApiType::AWS,
            $audio->getId()
        );


        if(200 == (int)$response->code)
        {
            $results = json_decode($response->body);
            if((int)$results->status == 200){
                $this->logger->info(FileUtil::getClassName(get_class()).":  successful queue to apostle ".$contentType." for aws processing ");
            }
        }else{
            $this->logger->critical(FileUtil::getClassName(get_class()).":  failed queue to apostle ".$contentType." for aws processing ");
        }

        return true;
    }

    /**
     * Update Failed task on Apostle service
     *
     * @param Audio $audio
     * @param $contentType
     * @param $correlationId
     * @param $error
     * @return bool
     */
    private function updateFailedPost(Audio $audio, $contentType, $correlationId, $error)
    {
        $audio->setStatus($this->statusService->error());

        if ($contentType == ContentType::PROMOTION) {
            $this->promoService->update($audio);
        } elseif ($contentType == ContentType::SLOGAN) {
            $this->sloganService->update($audio);
        } elseif ($contentType == ContentType::ADVERT) {
            $this->advertService->update($audio);
        }

        $response = $this->apostleRestFulClientService->queueFailedTask($correlationId, $error);

        if (200 == (int)$response->code) {
            $results = json_decode($response->body);
            if ((int)$results->status == 200) {
                $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully posted failed task back to apostle");
            }
        } else {
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to post failed task back to apostle ");
        }
        return true;
    }


}