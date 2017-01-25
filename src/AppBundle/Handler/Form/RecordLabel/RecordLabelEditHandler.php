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
namespace AppBundle\Handler\Form\RecordLabel;

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Status;
use AppBundle\Event\RecordLabel\RecordLabelEvent;
use AppBundle\Event\RecordLabel\RecordLabelEvents;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\Core\StatusServices;
use AppBundle\Service\RecordLabel\RecordLabelService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecordLabelEditHandler
{
    /**
     * @var RecordLabelService
     */
    private $recordLabelService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StatusServices
     */
    private $statusService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RecordLabelEditHandler constructor.
     * @param RecordLabelService $recordLabelService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param StatusServices $statusService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RecordLabelService $recordLabelService, FlashMessageService $alertService, LoggerInterface $logger, StatusServices $statusService, EventDispatcherInterface $eventDispatcher)
    {
        $this->recordLabelService = $recordLabelService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->statusService = $statusService;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @param Form $form
     * @param Status $currentStatus
     * @return bool
     */
    public function handle(Form $form,Status $currentStatus)
    {
        if(!$form->isSubmitted()){
            return false;
        }

        if(!$form->isValid()){
            $this->alertService->setError('There was an Error whilst submitting the form, please check all fields.');
            return false;
        }

        $recordLabel = $form->getData();

        try{
            $this->recordLabelService->update($recordLabel);
            $this->alertService->setSuccess(sprintf("You have successfully updated the record label: %s ",$recordLabel->getName()));

            //check status
            if($currentStatus->getCode() != $recordLabel->getStatus()->getCode()){
                if(($this->statusService->notVerified()->getCode() == $currentStatus->getCode()) && ($recordLabel->getStatus()->getCode() == $this->statusService->active()->getCode())){
                    //check if old status was 'Not verified' and The new status is 'Active'
                    //Trigger event
                    $this->eventDispatcher->dispatch(
                        RecordLabelEvents::ON_VERIFY,
                        new RecordLabelEvent($recordLabel)
                    );
                    $this->logger->info(FileUtil::getClassName(get_class()) . ": trigger event :".RecordLabelEvents::ON_VERIFY);
                }
            }

        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst creating the record label, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }


}