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
namespace AppBundle\Consumer\General;

use AppBundle\Common\Async;
use AppBundle\Common\FileUtil;
use AppBundle\Service\RadioShow\RadioShowService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class AsyncConsumer implements ConsumerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RadioShowService
     */
    private $radioShowService;

    /**
     * AsyncConsumer constructor.
     * @param LoggerInterface $logger
     * @param RadioShowService $radioShowService
     */
    public function __construct(LoggerInterface $logger, RadioShowService $radioShowService)
    {
        $this->logger = $logger;
        $this->radioShowService = $radioShowService;
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
            if($payload->asyncType == Async::RADIO_SHOW_TIME_SLOT){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": incoming asyncType :".Async::RADIO_SHOW_TIME_SLOT);
                $this->radioShowService->processRequestCreateTimeSlot($payload);
            }
        }catch(\Exception $e){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Failed process incoming radio station stream:".$e->getMessage());
        }
        return true;
    }

}