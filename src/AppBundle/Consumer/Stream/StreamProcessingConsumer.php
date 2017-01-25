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
namespace AppBundle\Consumer\Stream;

use AppBundle\Common\FileUtil;
use AppBundle\Common\StreamProcessType;
use AppBundle\Service\Artist\ArtistService;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\RadioShow\RadioShowService;
use AppBundle\Service\RecordLabel\RecordLabelService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class StreamProcessingConsumer implements ConsumerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RadioStationStreamService
     */
    private $radioStationStreamService;

    /**
     * @var RecordLabelService
     */
    private $recordLabelService;

    /**
     * @var ArtistService
     */
    private $artistService;

    /**
     * @var RadioShowService
     */
    private $radioShowService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleRestFulClientService;

    /**
     * StreamProcessingConsumer constructor.
     * @param LoggerInterface $logger
     * @param RadioStationStreamService $radioStationStreamService
     * @param RecordLabelService $recordLabelService
     * @param ArtistService $artistService
     * @param RadioShowService $radioShowService
     * @param ApostleRestFulClientService $apostleRestFulClientService
     */
    public function __construct(LoggerInterface $logger, RadioStationStreamService $radioStationStreamService, RecordLabelService $recordLabelService, ArtistService $artistService, RadioShowService $radioShowService, ApostleRestFulClientService $apostleRestFulClientService)
    {
        $this->logger = $logger;
        $this->radioStationStreamService = $radioStationStreamService;
        $this->recordLabelService = $recordLabelService;
        $this->artistService = $artistService;
        $this->radioShowService = $radioShowService;
        $this->apostleRestFulClientService = $apostleRestFulClientService;
    }


    /**
     * @param AMQPMessage $msg
     * @return bool
     * @throws \Exception
     */
    public function execute(AMQPMessage $msg)
    {
        $payload = json_decode($msg->body);
        try{
            //check "processingType"
            if($payload->processType == StreamProcessType::RECORD_LABEL){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": incoming processType :".StreamProcessType::RECORD_LABEL);
                $stream = $this->radioStationStreamService->getById($payload->streamId);
                if($stream){
                    $this->recordLabelService->processRecordLabelFromStream($stream);
                }
            }elseif($payload->processType == StreamProcessType::ARTIST){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": incoming processType :".StreamProcessType::ARTIST);
                $stream = $this->radioStationStreamService->getById($payload->streamId);
                if($stream){
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": processing processType :".StreamProcessType::ARTIST);
                    $this->artistService->processArtistFromStream($stream);
                }
            }elseif ($payload->processType == StreamProcessType::SHOW){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": incoming processType :".StreamProcessType::SHOW);
                $stream = $this->radioStationStreamService->getById($payload->streamId);
                if($stream){
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": processing processType :".StreamProcessType::SHOW);
                    $this->radioShowService->processShowFromStream($stream);
                }
            }
        }catch(\Exception $e){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Failed process incoming radio station stream:".$e->getMessage());
        }
        return true;
    }

}