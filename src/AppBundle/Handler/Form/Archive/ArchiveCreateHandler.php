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
namespace AppBundle\Handler\Form\Archive;

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Entity\Archive;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Audio\AudioEditorService;
use AppBundle\Service\Archive\ArchiveService;
use AppBundle\Service\RadioShow\RadioShowService;
use AppBundle\Service\RadioStation\RadioStationService;
use Faker\Provider\cs_CZ\DateTime;
use Psr\Log\LoggerInterface;


class ArchiveCreateHandler
{
    /**
     * @var ArchiveService
     */
    private $archiveService;

    /**
     * @var RadioStationService
     */
    private $radioStationService;

    /**
     * @var RadioShowService
     */
    private $radioShowService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AudioEditorService
     */
    private $audioEditorService;

    /**
     * @var ApostleRestFulClientService
     */
    private $apostleClient;

    /**
     * @var String
     */
    private $path;

    /**
     * ArchiveCreateHandler constructor.
     * @param ArchiveService $archiveService
     * @param RadioStationService $radioStationService
     * @param RadioShowService $radioShowService
     * @param LoggerInterface $logger
     * @param AudioEditorService $audioEditorService
     * @param ApostleRestFulClientService $apostleClient
     * @param String $path
     */
    public function __construct(ArchiveService $archiveService, RadioStationService $radioStationService, RadioShowService $radioShowService, LoggerInterface $logger, AudioEditorService $audioEditorService, ApostleRestFulClientService $apostleClient, $path)
    {
        $this->archiveService = $archiveService;
        $this->radioStationService = $radioStationService;
        $this->radioShowService = $radioShowService;
        $this->logger = $logger;
        $this->audioEditorService = $audioEditorService;
        $this->apostleClient = $apostleClient;
        $this->path = $path;
    }


    /**
     * @param null $folderName
     */
    public function handle($folderName = null)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ":  archive content started ");
        $dayOfTheWeek = null;

        if (!$folderName) {
            $date = new \DateTime();
            /**
             * Used to check files created before midnight
             */
            $checkDate = new \DateTime();
            $checkDate->setTime(0, 0, 0);
            $totalMinutes = floor(($date->getTimestamp() - $checkDate->getTimestamp()) / 60);
            if ($totalMinutes < 15) {
                $date->sub(new \DateInterval('P1D'));
            }
            $folderName = $date->format("Y_m_d");
        }

        try {
            $files = glob($this->path . '/' . $folderName . "/*.mp3");

            foreach ($files as $file) {

                /**
                 * if file last midified date is less than 1 minute ignore it
                 */
                $currentTime = new \DateTime();
                $totalMinutes = $currentTime->getTimestamp() - filemtime($file);
                $totalMinutes = floor($totalMinutes / 60);

                if (0 < $totalMinutes) {
                    $archive = new Archive();

                    $arrFileDetails = $this->audioEditorService->getDetails($file);
                    $archive->setDuration((int)$arrFileDetails['duration']);
                    $archive->setBitrate((int)$arrFileDetails['bit_rate']);
                    $archive->setSize(FileUtil::getHumanFileSize($arrFileDetails['size']));
                    $station = $this->radioStationService->getById($this->getStationId(basename($file)));
                    if ($station) {
                        $archive->setRadioStation($station);
                        $radioShow = $this->getShowId($station, basename($file));
                        if ($radioShow) {
                            $archive->setRadioShow($radioShow);
                        }
                    }

                    $tmp = explode('_', basename($file));
                    $playedAt = new \DateTime($tmp[0] . ' ' . str_replace('-', ':', $tmp[1]) . ':00');
                    $archive->setPlayedAt($playedAt);
                    $archive->setLocalFile(basename($file));
                    $archive->setRealFilePath($folderName . '/' . basename($file));
                    $archive->setTitle(basename($file));
                    //$this->archiveService->create($archive);

                    $response = $this->apostleClient->queueFileUpload(
                        ContentType::ARCHIVE,
                        ApiType::AWS,
                        $archive->getId()
                    );

                    if (200 == (int)$response->code) {
                        $results = json_decode($response->body);
                        if ((int)$results->status == 200) {
                            $this->logger->info(FileUtil::getClassName(get_class()) . ":  successful queue to apostle archive to bucket processing ");
                        }
                    } else {
                        $this->logger->critical(FileUtil::getClassName(get_class()) . ":  failed queue to apostle advert to bucket processing ");
                    }
                }


            }
        } catch (\UnexpectedValueException $e) {
            $this->logger->critical(FileUtil::getClassName(get_class()) . ": archive content failed:" . $e->getMessage());
        }
    }

    /**
     * @param $str
     * @return mixed
     */
    private function getStationId($str)
    {
        $tmp = explode('_', $str);
        return $tmp[3];
    }

    /**
     * @param $radioStation
     * @param $str
     * @return mixed
     */
    private function getShowId($radioStation, $str)
    {
        $tmp = explode('_', $str);

        $date = new \DateTime($tmp[0]);
        $dayOfTheWeek = $date->format('l');
        $strTime = str_replace('-', ':', $tmp[1]) . ':00';
        $results = $this->radioShowService->getByTimeSlots($radioStation, $strTime, $dayOfTheWeek);

        /**
         * If there is no results, try adjust time slot
         */
        if (count($results) == 0) {
            if ($strTime === '00:00:00') {
                $strTime = '23:59:59';
                $results = $this->radioShowService->getByTimeSlots($radioStation, $strTime, $dayOfTheWeek);
            }
        }


        if (count($results) == 1) {
            echo("===================");
            var_dump($results[0]->getId());
            return $results[0];
        } elseif (count($results) > 1) {
            /**
             * Handle shows that whose time slot is spread between 2 dates
             */

            if ($strTime === '00:00:00') {
                $strTime = '23:59:59';
            }

            foreach ($results as $show) {
                $tmp = explode(':', $strTime);
                $currentDate = new \DateTime();
                $currentDate->setTime($tmp[0], $tmp[1], $tmp[2]);

                $checkStartDate = $show->getStartTime();
                $checkStartDate->setDate($currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('d'));

                $checkEndDate = $show->getEndTime();
                $checkEndDate->setDate($currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('d'));

                $diff = $checkStartDate->diff($checkEndDate);
                if ($diff->h > 12) {
                    $checkEndDate->add(new \DateInterval('P1D'));
                }

                if (($currentDate >= $checkStartDate) && ($currentDate <= $checkEndDate)) {
                    echo("+++++++++");
                    var_dump($show->getId());
                    return $show;
                }
            }
        }
    }

}