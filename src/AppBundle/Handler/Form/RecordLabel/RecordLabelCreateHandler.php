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

use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\RecordLabel\RecordLabelService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class RecordLabelCreateHandler
{
    private $recordLabelService;
    private $alertService;
    private $logger;


    /**
     * RecordLabelCreateHandler constructor.
     * @param $recordLabelService
     * @param $alertService
     */
    public function __construct(
        RecordLabelService $recordLabelService,
        FlashMessageService $alertService,
        LoggerInterface $logger)
    {
        $this->recordLabelService = $recordLabelService;
        $this->alertService = $alertService;
        $this->logger = $logger;

    }

    /**
     * @param Form $form
     * @return bool
     */
    public function handle(Form $form)
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
            $this->recordLabelService->create($recordLabel);
            $this->alertService->setSuccess(sprintf("You have successfully added the record label: %s ",$recordLabel->getName()));
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst creating the record label, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }


}