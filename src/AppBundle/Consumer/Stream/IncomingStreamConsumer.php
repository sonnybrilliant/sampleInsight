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

use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvent;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvents;
use AppBundle\Service\Advert\AdvertService;
use AppBundle\Service\AdvertisingOrganization\AdvertisingOrganizationService;
use AppBundle\Service\Core\ContentTypeService;
use AppBundle\Service\Promo\PromoService;
use AppBundle\Service\Slogan\SloganService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use AppBundle\Service\Song\SongService;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use AppBundle\Service\RadioStation\RadioStationService;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class IncomingStreamConsumer implements ConsumerInterface
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
     * @var RadioStationService
     */
    private $radioStationService;

    /**
     * @var SongService
     */
    private $songService;

    /**
     * @var AdvertService
     */
    private $advertService;

    /**
     * @var AdvertisingOrganizationService
     */
    private $advertisingOrganizationService;

    /**
     * @var SloganService
     */
    private $sloganService;

    /**
     * @var PromoService
     */
    private $promoService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var bool
     */
    private $isSongFound = false;

    /**
     * IncomingStreamConsumer constructor.
     * @param LoggerInterface $logger
     * @param RadioStationStreamService $radioStationStreamService
     * @param RadioStationService $radioStationService
     * @param SongService $songService
     * @param AdvertService $advertService
     * @param AdvertisingOrganizationService $advertisingOrganizationService
     * @param SloganService $sloganService
     * @param PromoService $promoService
     * @param EventDispatcherInterface $eventDispatcher
     * @param ContentTypeService $contentTypeService
     */
    public function __construct(LoggerInterface $logger,
                                RadioStationStreamService $radioStationStreamService,
                                RadioStationService $radioStationService,
                                SongService $songService,
                                AdvertService $advertService,
                                AdvertisingOrganizationService $advertisingOrganizationService,
                                SloganService $sloganService,
                                PromoService $promoService,
                                EventDispatcherInterface $eventDispatcher,
                                ContentTypeService $contentTypeService)
    {
        $this->logger = $logger;
        $this->radioStationStreamService = $radioStationStreamService;
        $this->radioStationService = $radioStationService;
        $this->songService = $songService;
        $this->advertService = $advertService;
        $this->advertisingOrganizationService = $advertisingOrganizationService;
        $this->sloganService = $sloganService;
        $this->promoService = $promoService;
        $this->eventDispatcher = $eventDispatcher;
        $this->contentTypeService = $contentTypeService;
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
            $stream = $this->process($payload);

            if($this->isSongFound){
                //TODO Fire event let artist know song was detected
            }

        }catch(\Exception $e){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": Failed to save stream to the database error:".$e->getMessage());
            throw new \Exception("Failed to save stream to the database error:".$e->getMessage());
        }

        return true;

    }

    /**
     * Process Stream
     *
     * @param $payload
     * @return RadioStationStream
     */
    private function process($payload)
    {
        $radioStationStream = null;
        $this->logger->info(FileUtil::getClassName(get_class()) . ": Process incoming payload");

        try{
            //check content type Music / Custom
            if(isset($payload->data->metadata->custom_files[0]->title)){
                //custom content
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Process custom content");
                $radioStationStream = $this->processCustomContent($payload);
            }else{
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Process music content");
                $radioStationStream = $this->processMusicContent($payload);
            }

            /**
             * Check which show the stream was captured on
             */
            if($radioStationStream){
                $this->eventDispatcher->dispatch(
                    RadioStationStreamEvents::ON_STREAM_CHECK_SHOW,
                    new RadioStationStreamEvent($radioStationStream)
                );
            }
        }catch (\Exception $e){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ":  failed Process incoming payload streamId:".$payload->streamId);
        }

        return $radioStationStream;

    }

    /**
     * Process music content
     *
     * @param $payload
     * @return RadioStationStream
     */
    public function processMusicContent($payload)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing music content payload");
        $song = null;
        $data = $payload->data;

        $radioStationStream = new RadioStationStream();

        try{
            /**
             * Find song local by ISRC
             */
            if(isset($data->metadata->music[0]->external_ids->isrc)){
                $isrc = $data->metadata->music[0]->external_ids->isrc;
                $song = $this->songService->getByISRC($isrc);
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing song ISRC:".$isrc);
            }

            $playedAt = new \DateTime($data->metadata->timestamp_utc);
            $playedAt->setTimezone(new \DateTimeZone('Africa/Johannesburg'));
            $playedAt->add(new \DateInterval('PT2H'));

            /**
             * Get Radio station by Stream ID
             */
            $radioStation = $this->radioStationService->getByStreamId($payload->streamId);



            $radioStationStream->setStreamId($payload->streamId);
            $radioStationStream->setRadioStation($radioStation);
            $radioStationStream->setVersion($data->status->version);
            $radioStationStream->setPlayedAt($playedAt);

            if(isset($data->metadata->music[0]->release_date)){
                $radioStationStream->setReleaseAt(new \DateTime($data->metadata->music[0]->release_date, new \DateTimeZone('Africa/Johannesburg')));
            }

            $radioStationStream->setDuration($data->metadata->played_duration);

            if(isset($data->metadata->music[0]->album->name)){
                $radioStationStream->setAlbum($data->metadata->music[0]->album->name);
            }

            $radioStationStream->setTitle($data->metadata->music[0]->title);
            $radioStationStream->setAcrid($data->metadata->music[0]->acrid);

            if(isset($data->metadata->music[0]->label)){
                $radioStationStream->setLabel($data->metadata->music[0]->label);
            }

            $radioStationStream->setData($data);

            $radioStationStream->setArtist($data->metadata->music[0]->artists[0]->name);
            $this->logger->info(FileUtil::getClassName(get_class()) . ": song artist:".$radioStationStream->getArtist());


            if(isset($data->metadata->music[0]->external_ids->isrc)){
                $radioStationStream->setIsrc($data->metadata->music[0]->external_ids->isrc);
            }

            if(isset($data->metadata->music[0]->external_ids->upc)){
                $radioStationStream->setUpc($data->metadata->music[0]->external_ids->upc);
            }

            $radioStationStream->setContentType($this->contentTypeService->music());
            $radioStationStream->setStreamURL($payload->streamURL);
            $radioStationStream->setWasAddedViaQueue(true);

            if($song){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Song exist in the system, updating stream");

                /**
                 * Song match has been found
                 */
                $radioStationStream->setSong($song);
                $radioStationStream->setArtistObject($song->getArtist());
                $radioStationStream->setRecordLabel($song->getRecordLabel());

                //update song is found
                $this->isSongFound = true;

                if(isset($data->metadata->music[0]->external_metadata->youtube->vid)){
                   if(!$song->getYouTubeId()){
                       $song->setYouTubeId($data->metadata->music[0]->external_metadata->youtube->vid);
                       $this->songService->update($song);
                   }
                }
            }

            $this->radioStationStreamService->create($radioStationStream);

            /**
             * Process transaction
             */
            if(!$this->isSongFound){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Song ".$radioStationStream->getTitle()." does not exist in the system, artist:".$radioStationStream->getArtist());

                $this->eventDispatcher->dispatch(
                    RadioStationStreamEvents::ON_STREAM_INCOMING,
                    new RadioStationStreamEvent($radioStationStream)
                );
            }
        }catch(\Exception $e){
            $this->logger->critical(FileUtil::getClassName(get_class()) . ":  failed to process streamId:".$payload->streamId." error:".$e->getMessage());
        }


        return $radioStationStream;
    }

    /**
     * Process Custom content
     *
     * @param $payload
     * @return RadioStationStream
     */
    public function processCustomContent($payload)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing custom content payload");

        $data = $payload->data;
        $content = null;
        $contentType = null;

        $playedAt = new \DateTime($data->metadata->timestamp_utc);
        $playedAt->setTimezone(new \DateTimeZone('Africa/Johannesburg'));
        $playedAt->add(new \DateInterval('PT2H'));

        /**
         * Get Radio station by Stream ID
         */
        $radioStation = $this->radioStationService->getByStreamId($payload->streamId);

        $radioStationStream = new RadioStationStream();

        $radioStationStream->setStreamId($payload->streamId);
        $radioStationStream->setRadioStation($radioStation);
        $radioStationStream->setVersion($data->status->version);
        $radioStationStream->setPlayedAt($playedAt);

        //get type of content
        if(isset($data->metadata->custom_files[0]->type)){
            $contentType = $data->metadata->custom_files[0]->type;
            if(ContentType::PROMOTION == $data->metadata->custom_files[0]->type){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing custom content type:".ContentType::PROMOTION);
                $radioStationStream->setContentType($this->contentTypeService->promo());

                //find slogan by code
                $promo = $this->promoService->getByCode($data->metadata->custom_files[0]->audio_id);
                if($promo){
                    $radioStationStream->setPromo($promo);

                    //Update Promo
                    $promo->setPlayCount($promo->getPlayCount() + 1);
                    $promo->setLastPlayedAt($playedAt);
                    $this->promoService->update($promo);
                }


            }else if(ContentType::SLOGAN == $data->metadata->custom_files[0]->type){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing custom content type:".ContentType::SLOGAN);
                $radioStationStream->setContentType($this->contentTypeService->slogan());

                //find slogan by code
                $slogan = $this->sloganService->getByCode($data->metadata->custom_files[0]->audio_id);
                if($slogan){
                   $radioStationStream->setSlogan($slogan);

                   //update slogan
                    $slogan->setPlayCount($slogan->getPlayCount() + 1);
                    $slogan->setLastPlayedAt($playedAt);
                    $this->sloganService->update($slogan);

                }


            }else if(ContentType::ADVERT == $data->metadata->custom_files[0]->type){
                $this->logger->info(FileUtil::getClassName(get_class()) . ": Processing custom content type:".ContentType::ADVERT);

                $radioStationStream->setContentType($this->contentTypeService->advertisement());

                // find advert by code
                $advert = $this->advertService->getByCode($data->metadata->custom_files[0]->audio_id);
                if($advert){
                    $radioStationStream->setAdvert($advert);
                    $radioStationStream->setAdvertisingOrganization($advert->getAdvertisingOrganization());

                    //update Advert
                    $advert->setPlayCount($advert->getPlayCount() + 1);
                    $advert->setLastPlayedAt($playedAt);
                    $this->advertService->update($advert);

                    //update organization
                    $organization = $advert->getAdvertisingOrganization();
                    $organization->setLastActiveAt($playedAt);
                    $this->advertisingOrganizationService->update($organization);

                    $this->radioStationStreamService->updateStreamsWithAdvertDetails($advert);
                }
            }else{
                $this->logger->critical(FileUtil::getClassName(get_class()) . ": Content type not found:".$data->metadata->custom_files[0]->type);
            }

        }

        $radioStationStream->setDuration($data->metadata->played_duration);
        $radioStationStream->setAlbum($contentType);
        $radioStationStream->setTitle($data->metadata->custom_files[0]->title);
        $radioStationStream->setAcrid($data->metadata->custom_files[0]->acrid);
        $radioStationStream->setData($data);
        $radioStationStream->setArtist($data->metadata->custom_files[0]->artist);
        $radioStationStream->setCount($data->metadata->custom_files[0]->count);
        $radioStationStream->setBucketId($data->metadata->custom_files[0]->bucket_id);
        $radioStationStream->setAudioFileId($data->metadata->custom_files[0]->audio_id);

        $radioStationStream->setStreamURL($payload->streamURL);
        $radioStationStream->setWasAddedViaQueue(true);

        /**
         * TODO Match content with local copy
         */

        $this->radioStationStreamService->create($radioStationStream);
        $this->logger->info(FileUtil::getClassName(get_class()) . ": Done Processing custom content type:".$contentType);
        return $radioStationStream;
    }

}