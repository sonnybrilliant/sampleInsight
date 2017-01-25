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
namespace AppBundle\Handler\RadioShowTimeSlot;

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Common\FileUtil;
use AppBundle\Entity\Archive;
use AppBundle\Entity\RadioShow;
use AppBundle\Entity\RadioShowTimeSlot;
use AppBundle\Service\Core\Apostle\ApostleRestFulClientService;
use AppBundle\Service\Core\Audio\AudioEditorService;
use AppBundle\Service\Archive\ArchiveService;
use AppBundle\Service\RadioShow\RadioShowService;
use AppBundle\Service\RadioShow\RadioShowTimeSlotService;
use AppBundle\Service\RadioStation\RadioStationService;
use Faker\Provider\cs_CZ\DateTime;
use Psr\Log\LoggerInterface;


class RadioShowTimeSlotCreateHandler
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RadioShowTimeSlotService
     */
    private $radioShowTimeSlotService;

    /**
     * RadioShowTimeSlotCreateHandler constructor.
     * @param LoggerInterface $logger
     * @param RadioShowTimeSlotService $radioShowTimeSlotService
     */
    public function __construct(LoggerInterface $logger, RadioShowTimeSlotService $radioShowTimeSlotService)
    {
        $this->logger = $logger;
        $this->radioShowTimeSlotService = $radioShowTimeSlotService;
    }


    /**
     * Create Radio Show Time Slots
     * @param RadioShow $radioShow
     * @param $payload
     * @throws \Exception
     */
    public function handle(RadioShow $radioShow,$payload)
    {
        $this->logger->info(FileUtil::getClassName(get_class()) . ":  create radio show time slot ");

        //get month
        if(is_numeric($payload->month)){
            $startDate = new \DateTime();
            //create the right month
            $startDate->setDate($startDate->format('Y'),$payload->month,1);
            $startDate->modify('first day of this month');
            $lastDayOfMonth = new \DateTime();
            $lastDayOfMonth->setTimestamp($startDate->getTimestamp());
            $lastDayOfMonth->modify('last day of this month');

            $isPlaying = false;
            while($startDate <= $lastDayOfMonth){
                //0 - 6 , 0 = sunday
                $dayOfTheWeek = $startDate->format('w');

                if(($dayOfTheWeek == 0) && ($radioShow->getPlaysSunday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 1) && ($radioShow->getPlaysMonday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 2) && ($radioShow->getPlaysTuesday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 3) && ($radioShow->getPlaysWednesday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 4) && ($radioShow->getPlaysThursday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 5) && ($radioShow->getPlaysFriday())){
                    $isPlaying = true;
                }elseif (($dayOfTheWeek == 6) && ($radioShow->getPlaysSaturday())){
                    $isPlaying = true;
                }

                if($isPlaying){
                    $this->createDateWithTimeSlots($radioShow,$startDate);
                }

                $isPlaying = false;
                $startDate->add(new \DateInterval('P1D'));
            }
        }else{
            throw new \Exception("Create radio show time slot, month must be numeric:".$payload->month);
        }
    }

    /**
     * @param RadioShow $radioShow
     * @param \DateTime $date
     */
    private function createDateWithTimeSlots(RadioShow $radioShow,\DateTime $date)
    {
        $radioShowTimeSlot = new RadioShowTimeSlot();

        $startAt = clone $date;

        $startTime = $radioShow->getStartTime();
        $endTime = $radioShow->getEndTime();

        $startAt->setTime($startTime->format('H'),$startTime->format('i'),$startTime->format('s'));

        $endAt = clone $startAt;
        $endAt->setTime($endTime->format('H'),$endTime->format('i'),$endTime->format('s'));

        $diff = $startAt->diff($endAt);
        if (($diff->h > 12) && ($radioShow->getIsCrossOver())) {
            $endAt->add(new \DateInterval('P1D'));
        }



        $radioShowTimeSlot->setRadioStation($radioShow->getRadioStation());
        $radioShowTimeSlot->setShow($radioShow);
        $radioShowTimeSlot->setStartAt($startAt);
        $radioShowTimeSlot->setEndAt($endAt);
        $radioShowTimeSlot->setWeekOf($date->format('W'));

        $this->radioShowTimeSlotService->create($radioShowTimeSlot);
    }

}