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

use AppBundle\Common\Audio;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Service\Advert\AdvertService;
use AppBundle\Service\Archive\ArchiveService;
use Aws\Result;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Aws\AwsUploadAudioService;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\Promo\PromoService;
use AppBundle\Service\Slogan\SloganService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;


class UploadFileAWSS3BucketConsumer implements ConsumerInterface
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
     * @var ArchiveService
     */
    private $archiveService;

    /**
     * @var AwsUploadAudioService
     */
    private $awsUploadAudioService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleRestFulClientService;

    /**
     * UploadFileAWSS3BucketConsumer constructor.
     * @param LoggerInterface $logger
     * @param StatusServices $statusService
     * @param SloganService $sloganService
     * @param AdvertService $advertService
     * @param PromoService $promoService
     * @param ArchiveService $archiveService
     * @param AwsUploadAudioService $awsUploadAudioService
     * @param ApostleRestFulClientService $apostleRestFulClientService
     */
    public function __construct(LoggerInterface $logger, StatusServices $statusService, SloganService $sloganService, AdvertService $advertService, PromoService $promoService, ArchiveService $archiveService, AwsUploadAudioService $awsUploadAudioService, ApostleRestFulClientService $apostleRestFulClientService)
    {
        $this->logger = $logger;
        $this->statusService = $statusService;
        $this->sloganService = $sloganService;
        $this->advertService = $advertService;
        $this->promoService = $promoService;
        $this->archiveService = $archiveService;
        $this->awsUploadAudioService = $awsUploadAudioService;
        $this->apostleRestFulClientService = $apostleRestFulClientService;
    }


    /**
     * @param AMQPMessage $msg
     * @return bool
     * @throws \Exception
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": aws incoming rabbitmq message");
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
    private function process(\stdClass $payload)
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
        } elseif ($payload->contentType == ContentType::ARCHIVE){
            $audio = $this->archiveService->getById($payload->fileId);
            $contentType = ContentType::ARCHIVE;
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content type:" . ContentType::ARCHIVE);
        }

        /**
         * Once content type is established, process request to upload content
         */
        if ($audio) {
            $this->logger->info(FileUtil::getClassName(get_class()) . ": content is valid, ready to upload");
            $this->processContent($audio,$contentType,$payload);
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
     */
    private function processContent(Audio $audio, $contentType,$payload)
    {
        /**
         * Upload file
         */
        try {
            $this->logger->info(FileUtil::getClassName(get_class()) . ": uploading content type:" . $contentType. " Id:" . $payload->fileId);
            $file = $audio->getRealFilePath();

            if($contentType == ContentType::ARCHIVE){
               $file = $this->archiveService->getRecordingsPath().'/'.$audio->getRealFilePath();
            }

            $results = $this->awsUploadAudioService->uploadContent($file,$contentType);

            if(isset($results['ObjectURL'])){
                /**
                 * Successful Post
                 * - Update content
                 */
                $audio->setIsUploadedToS3(true);
                $audio->setS3File($results['ObjectURL']);
                $audio->setS3SignatureFile($results['ETag']);
                $this->updateSuccessfulPost($audio, $contentType);
                $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully uploaded content type:" . $contentType . " Id:" . $payload->fileId);

            }
        } catch (\Exception $e) {
            $audio->setError($e->getMessage());
            $this->updateFailedPost($audio, $contentType, $payload->correlationId, $e->getMessage());
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed uploading content type:" . $contentType . " Id:" . $payload->fileId . " error:" . $e->getMessage());
        }
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
        $audio->setUploadedToS3At(new \DateTime());
        $canDeleteFile = false;
        /**
         * Check if ACRCLOUD is done
         */
        if ($audio->getIsUploadedToS3() && $audio->getIsUploadedToBucket()) {
            $audio->setStatus($this->statusService->active());
            $canDeleteFile = true;
        }

        if ($contentType == ContentType::PROMOTION) {
            $this->promoService->update($audio);
        }elseif ($contentType == ContentType::SLOGAN){
            $this->sloganService->update($audio);
        }elseif ($contentType == ContentType::ADVERT){
            $this->advertService->update($audio);
        }elseif ($contentType == ContentType::ARCHIVE){
            $this->archiveService->update($audio);
        }

        /**
         * Unlink local file
         */
        if($canDeleteFile ){

            if($contentType == ContentType::ARCHIVE){
                if(!unlink($this->archiveService->getRecordingsPath().'/'.$audio->getRealFilePath())){
                    $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to unlink file:" . $this->archiveService->getRecordingsPath().'/'.$audio->getRealFilePath());
                }else{
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully to unlinked file:" . $this->archiveService->getRecordingsPath().'/'.$audio->getRealFilePath());
                }
            }else{
                if(!unlink($audio->getRealFilePath())){
                    $this->logger->critical(FileUtil::getClassName(get_class()) . ": failed to unlink file:" . $audio->getRealFilePath());
                }else{
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": successfully to unlinked file:" . $audio->getRealFilePath());
                }
            }

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
        } elseif ($contentType == ContentType::ARCHIVE){
            $this->archiveService->update($audio);
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