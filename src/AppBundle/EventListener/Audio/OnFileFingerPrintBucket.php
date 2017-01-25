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
namespace AppBundle\EventListener\Audio;

use AppBundle\Service\Core\Acrcloud\AcrcloudUploadAudioService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Audio\AudioEvent;
use AppBundle\Event\Audio\AudioEvents;
use Doctrine\ORM\EntityManager;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class OnFileFingerPrintBucket implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var AcrcloudUploadAudioService
     */
    private $acrcloudUploadAudioService;

    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var String
     */
    private $routeKey;

    /**
     * OnUserCompilerCreate constructor.
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param AcrcloudUploadAudioService $acrcloudUploadAudioService
     * @param Producer $producer
     * @param String $routeKey
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        AcrcloudUploadAudioService $acrcloudUploadAudioService,
        Producer $producer,
        $routeKey
)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->acrcloudUploadAudioService = $acrcloudUploadAudioService;
        $this->producer = $producer;
        $this->routeKey = $routeKey;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            AudioEvents::UPLOAD_SLOGAN_FINGER_PRINTING => 'onFileReceive'
        );
    }

    /**
     * Process Audio file
     *
     * @param AudioEvent $audioEvent
     */
    public function onFileReceive(AudioEvent $audioEvent)
    {
        $audio = $audioEvent->getAudio();

        $payload = new \stdClass();
        $currentDate = new \DateTime();

        $payload->id = $audio->getId();
        $payload->timestamp = time();
        $payload->date = $currentDate->format('c');


        if('AppBundle\Entity\Slogan' == get_class($audio)){
            $payload->type = "slogan";
        }elseif('AppBundle\Entity\Advert'  == get_class($audio)){
            $payload->type = "advert";
        }elseif("Promo" == get_class($audio)){
            $payload->type = "promo";
        }

        return $this->sendToQueue($payload);
    }

    /**
     * Send payload to queue
     *
     * @param \stdClass $payload
     */
    private function sendToQueue(\stdClass $payload)
    {
        $this->producer->setContentType('application/json');
        $this->producer->setDeliveryMode(AMQPMessage::DELIVERY_MODE_PERSISTENT);
        $this->producer->publish(\json_encode($payload),$this->routeKey);
        return;
    }

}