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

namespace AppBundle\Service\RadioStation;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Artist;
use AppBundle\Entity\RecordLabel;
use AppBundle\Entity\Song;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Slogan;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvent;
use AppBundle\Event\RadioStationStream\RadioStationStreamEvents;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\RadioStationStream;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RadioStationStreamService
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
     * @var Producer
     */
    private $producer;

    /**
     * @var String
     */
    private $routingKeyMonitoringPush;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RadioStationStreamService constructor.
     *
     * @param LoggerInterface $logger
     * @param EntityManager $em
     * @param Producer $producer
     * @param $routingKey
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        LoggerInterface $logger,
        EntityManager $em,
        Producer $producer,
        $routingKey,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->producer = $producer;
        $this->routingKeyMonitoringPush = $routingKey;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $id
     * @return RadioStationStream|null|object
     */
    public function getById($id)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->find($id);
    }

    /**
     * Get This week top Songs
     *
     * @param $id
     * @return array
     */
    public function getWeekTopSongsByRadioStationId($id)
    {
        return $this->em->getRepository("AppBundle:RadioStationStream")->getWeekTopSongsByRadioStation($id);
    }

    /**
     * @param $range
     * @return array
     */
    public function getDashboardTopSongs($range)
    {
        return $this->em->getRepository("AppBundle:RadioStationStream")->getDashboardTopSongs($range);
    }

    /**
     * Get This week top Artist
     *
     * @param $id
     * @return array
     */
    public function getWeekTopArtistsByRadioStationId($id)
    {
        return $this->em->getRepository("AppBundle:RadioStationStream")->getWeekTopArtistsByRadioStation($id);
    }

    /**
     * Get overall top Artist
     *
     * @param $range
     * @return array
     */
    public function getDashboardTopArtists($range)
    {
        return $this->em->getRepository("AppBundle:RadioStationStream")->getDashboardTopArtists($range);
    }

    /**
     * Create a new stream
     *
     * @param RadioStationStream $radioStationStream
     */
    public function create(RadioStationStream $radioStationStream)
    {
        $this->em->persist($radioStationStream);
        $this->em->flush();
    }

    /**
     * Process Top Artist data for Dashboard chats and Radio Station chart (Echart)
     *
     * @param $arrData
     * @return array
     */
    public function processEchartsDataForTopArtist($arrData)
    {
        $arrFinal = array();

        if(sizeof($arrData) > 0){
            $arrNew = [];
            $arrTmp = [];
            for($x = 0; $x < sizeof($arrData) ; $x++){
                if(!in_array($arrData[$x]['artist'],$arrTmp)){
                    $arrNew[] = array(
                        'value' => $arrData[$x]['played'],
                        'name' =>  $arrData[$x]['artist']
                    );

                    $arrTmp[] = $arrData[$x]['artist'];
                }
            }

            $arrFinal['data'] = $arrNew;
            $arrFinal['labels'] = $arrTmp;
        }

        return $arrFinal;
    }

    /**
     * Get songs week top radio stations plays
     *
     * @param $songId
     * @return array
     */
    public function getSongWeekTopRadioStationPlays($songId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getWeekSongTopRadioStationPlays($songId);
    }

    /**
     * Get songs last week top radio stations plays
     *
     * @param $songId
     * @return array
     */
    public function getSongLastWeekTopRadioStationPlays($songId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getLastWeekSongTopRadioStationPlays($songId);
    }

    /**
     * Get Artist week top songs
     *
     * @param $artistId
     * @return array
     */
    public function getArtistWeekTopSongs($artistId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getArtistWeekTopSongsPlays($artistId);
    }

    /**
     * Get Artist last week top songs
     * @param $artistId
     * @return array
     */
    public function getArtistLastWeekTopSongs($artistId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getArtistLastWeekTopSongsPlays($artistId);
    }

    /**
     * @param $songId
     * @return array
     */
    public function getSongCurrentMonthTopRadioStationPlays($songId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getCurrentMonthSongTopRadioStationPlays($songId);
    }

    public function getArtistCurrentMonthTopSongsPlays($artistId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getCurrentMonthArtistTopSongPlays($artistId);
    }

    /**
     * @param $songId
     * @return array
     */
    public function getSongLastMonthTopRadioStationPlays($songId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getLastMonthSongTopRadioStationPlays($songId);
    }

    /**
     * @param $songId
     * @return array
     */
    public function getSongHistoricalSongRadioStationPlays($songId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getHistoricalSongRadioStationPlays($songId);
    }

    /**
     * @param $artistId
     * @return array
     */
    public function getArtistLastMonthTopSongsPlays($artistId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getLastMonthArtistTopSongsPlays($artistId);
    }

    /**
     * @param $artistId
     * @return array
     */
    public function getArtistHistoricalTopSongsPlays($artistId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getHistoricalArtistTopSongsPlays($artistId);
    }

    /**
     * Get advert week radio stations plays
     *
     * @param $advertId
     * @return array
     */
    public function getAdvertWeekRadioStationPlays($advertId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getWeekAdvertRadioStationPlays($advertId);
    }

    /**
     * Get advert last week radio stations plays
     *
     * @param $advertId
     * @return array
     */
    public function getAdvertLastWeekRadioStationPlays($advertId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getLastWeekAdvertRadioStationPlays($advertId);
    }

    /**
     * @param $advertId
     * @return array
     */
    public function getAdvertCurrentMonthRadioStationPlays($advertId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getCurrentMonthAdvertRadioStationPlays($advertId);
    }

    /**
     * @param $advertId
     * @return array
     */
    public function getAdvertLastMonthRadioStationPlays($advertId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getLastMonthAdvertRadioStationPlays($advertId);
    }

    /**
     * @param $advertId
     * @return array
     */
    public function getAdvertHistoricalRadioStationPlays($advertId)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->getHistoricalAdvertRadioStationPlays($advertId);
    }

    /**
     *
     * @param $arrData
     * @return array
     */
    public function processEchartsDataForSongTopRadioStationPlays($arrData)
    {
        $arrFinal = array();
        if(sizeof($arrData) > 0){
            $arrNew = [];
            $arrTmp = [];
            for($x = 0; $x < count($arrData) ; $x++){
                if(!in_array($arrData[$x]['name'],$arrTmp)){
                    $arrNew[] = array(
                        'value' => $arrData[$x]['played'],
                        'name' =>  $arrData[$x]['name']
                    );

                    $arrTmp[] = $arrData[$x]['name'];
                }
            }

            $arrFinal['data'] = $arrNew;
            $arrFinal['labels'] = $arrTmp;
        }

        return $arrFinal;
    }

    /**
     * Queue incoming monitoring stream
     *
     * @param $streamURL
     * @param $streamId
     * @param $jsonData
     */
    public function queuePayload($streamURL,$streamId,$jsonData)
    {
        $currentDate = new \DateTime();

        $payload = new \stdClass();
        $payload->processType = "incoming_stream";
        $payload->data = \json_decode($jsonData);
        $payload->timestamp = time();
        $payload->date = $currentDate->format('c');
        $payload->streamId = $streamId;
        $payload->streamURL = $streamURL;

        $this->producer->setContentType('application/json');
        $this->producer->setDeliveryMode(AMQPMessage::DELIVERY_MODE_PERSISTENT);
        $this->producer->publish(\json_encode($payload),$this->routingKeyMonitoringPush);

    }

    /**
     * Update recorded stream with songs ISRC code.
     * - legacy
     * @param Song $song
     * @return int
     */
    public function updateStreamsWithSongDetails(Song $song)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->updateSongDetailsBySong($song);
    }

    /**
     * Update recorded stream with advert code.
     * - legacy
     * @param Advert $advert
     * @return int
     */
    public function updateStreamsWithAdvertDetails(Advert $advert)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->updateAdvertDetailsByAdvert($advert);
    }

    /**
     * Update recorded stream with slogan code.
     * - legacy
     * @param Slogan $slogan
     * @return int
     */
    public function updateStreamsWithSloganDetails(Slogan $slogan)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->updateAdvertDetailsBySlogan($slogan);
    }

    /**
     * Update stream by verified record label
     *
     * @param RecordLabel $recordLabel
     * @return int
     */
    public function updateStreamByVerifiedRecordLabel(RecordLabel $recordLabel)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->updateContentLocalityByVerifiedRecordLabel($recordLabel);
    }

    /**
     * @param Artist $artist
     * @return mixed
     */
    public function updateStreamByVerifiedArtist(Artist $artist)
    {
        return $this->em->getRepository('AppBundle:RadioStationStream')->updateContentLocalityByVerifiedArtist($artist);
    }

    /**
     * Re run streams without Artist Id
     */
    public function rerunRadioStreamData()
    {
        $streams = $this->em->getRepository('AppBundle:RadioStationStream')->getStreamsWithoutArtistId();

        if($streams){
            foreach ($streams as $stream){
                //check ISRC code
                $isrc = $stream->getIsrc();
                if(strlen($isrc) > 5){
                    if(substr($isrc,0,2) == 'ZA'){
                        //then song is local
                        $stream->setIsLocal(true);
                        $stream->setIsAfrican(true);
                        $this->em->persist($stream);
                        $this->em->flush();
                    }
                }

                $this->eventDispatcher->dispatch(
                    RadioStationStreamEvents::ON_STREAM_INCOMING,
                    new RadioStationStreamEvent($stream)
                );
            }
        }
    }
}