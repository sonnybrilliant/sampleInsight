<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/03
 */
namespace AppBundle\Handler\RadioStation;

use AppBundle\Entity\RadioStationStream;
use AppBundle\Service\Core\ContentTypeService;
use AppBundle\Service\RadioStation\RadioStationService;
use AppBundle\Service\RadioStation\RadioStationStreamService;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class RadioStationStreamCreateHandler
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
     * @var ContentTypeService
     */
    private $contentTypeService;

    /**
     * RadioStationStreamCreateHandler constructor.
     *
     * @param LoggerInterface $logger
     * @param RadioStationStreamService $radioStationStreamService
     * @param RadioStationService $radioStationService
     * @param ContentTypeService $contentTypeService
     */
    public function __construct(
        LoggerInterface $logger,
        RadioStationStreamService $radioStationStreamService,
        RadioStationService $radioStationService,
        ContentTypeService $contentTypeService
    )
    {
        $this->radioStationStreamService = $radioStationStreamService;
        $this->radioStationService = $radioStationService;
        $this->logger = $logger;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Create monitoring stream
     *
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request)
    {

        if (!$request->get('stream_id') && !$request->get('data') && !$request->get('stream_url')) {
            $msg = "Invalid post from IP:".$request->getClientIp();
            $this->logger->error($msg);
            return false;
        }

        $streamId  = $request->get('stream_id');
        $jsonData  = $request->get('data');
        $streamURL = $request->get('stream_url');

        /**
         * Queue payload for processing later, if queueing fails save to DB
         */
        try{
            $this->saveToQueue($streamURL,$streamId,$jsonData);
        }Catch(\Exception $e){
            $this->logger->error("Failed to queue payload for radio station with stream ID:".$streamId." Error:".$e->getMessage());
            /**
             * Save to DB
             */
            $this->saveToDB($streamURL,$streamId,$jsonData);
        }

        return true;
    }

    /**
     * @param $streamURL
     * @param $streamId
     * @param $jsonData
     * @return bool
     */
    private function saveToQueue($streamURL,$streamId,$jsonData)
    {
        $this->logger->info("Save stream to queue for radio station with stream ID:".$streamId);
        $this->radioStationStreamService->queuePayload($streamURL,$streamId,$jsonData);
        return true;
    }

    /**
     * @param $streamURL
     * @param $streamId
     * @param $jsonData
     * @return bool
     */
    private function saveToDB($streamURL,$streamId,$jsonData)
    {
        $this->logger->warning("Save stream to queue for radio station with stream ID:".$streamId);

        $radioStation = $this->radioStationService->getByStreamId($streamId);

        if ($radioStation) {

            $data = json_decode($jsonData);

            $playedAt = new \DateTime($data->metadata->timestamp_utc);
            $playedAt->setTimezone(new \DateTimeZone('Africa/Johannesburg'));
            $playedAt->add(new \DateInterval('PT2H'));

            $radioStationStream = new RadioStationStream();
            $radioStationStream->setStreamId($streamId);
            $radioStationStream->setRadioStation($radioStation);
            if($data->status->version){
                $radioStationStream->setVersion($data->status->version);
            }

            $radioStationStream->setPlayedAt($playedAt);

            // if content type is music
            if(isset($data->metadata->music)){
                $radioStationStream->setContentType($this->contentTypeService->music());

                if($data->metadata->music[0]->release_date){
                    $radioStationStream->setReleaseAt(new \DateTime($data->metadata->music[0]->release_date, new \DateTimeZone('Africa/Johannesburg')));
                }
                $radioStationStream->setDuration($data->metadata->played_duration);

                if($data->metadata->music[0]->album->name){
                    $radioStationStream->setAlbum($data->metadata->music[0]->album->name);
                }

                $radioStationStream->setTitle($data->metadata->music[0]->title);
                $radioStationStream->setAcrid($data->metadata->music[0]->acrid);

                if($data->metadata->music[0]->label){
                    $radioStationStream->setLabel($data->metadata->music[0]->label);
                }

                $radioStationStream->setData($data);
                $radioStationStream->setArtist($data->metadata->music[0]->artists[0]->name);

                if($data->metadata->music[0]->external_ids->isrc){
                    $radioStationStream->setIsrc($data->metadata->music[0]->external_ids->isrc);
                }

                if($data->metadata->music[0]->external_ids->upc){
                    $radioStationStream->setUpc($data->metadata->music[0]->external_ids->upc);
                }

            }else{
                //get type of content
                if(isset($data->metadata->custom_files[0]->type)){
                    if("promo" == strtolower($data->metadata->custom_files[0]->type)){
                        $radioStationStream->setContentType($this->contentTypeService->promo());
                    }else if("slogan" == strtolower($data->metadata->custom_files[0]->type)){
                        $radioStationStream->setContentType($this->contentTypeService->slogan());
                    }else if("advertisement" == strtolower($data->metadata->custom_files[0]->type)){
                        $radioStationStream->setContentType($this->contentTypeService->advertisement());
                    }else{
                        $this->logger->error("Content type not found:".$data->metadata->custom_files[0]->type);
                    }

                }

                $radioStationStream->setDuration($data->metadata->played_duration);

                if(isset($data->metadata->custom_files[0]->album)){
                    $radioStationStream->setAlbum($data->metadata->custom_files[0]->album);
                }

                $radioStationStream->setTitle($data->metadata->custom_files[0]->title);
                $radioStationStream->setAcrid($data->metadata->custom_files[0]->acrid);
                $radioStationStream->setData($data);
                $radioStationStream->setArtist($data->metadata->custom_files[0]->artist);
                $radioStationStream->setCount($data->metadata->custom_files[0]->count);
                $radioStationStream->setBucketId($data->metadata->custom_files[0]->bucket_id);
                $radioStationStream->setAudioFileId($data->metadata->custom_files[0]->audio_id);
            }

            $radioStationStream->setStreamURL($streamURL);
            $radioStationStream->setWasAddedViaQueue(false);

            $this->radioStationStreamService->create($radioStationStream);

        }else{
            $this->logger->error("Could not find radio station by steamId".$streamId);
        }
        return true;
    }
}