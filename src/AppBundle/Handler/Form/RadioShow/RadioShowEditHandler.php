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
namespace AppBundle\Handler\Form\RadioShow;

use AppBundle\Entity\RadioShow;
use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\RadioShow\RadioShowService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class RadioShowEditHandler
{
    /**
     * @var RadioShowService
     */
    private $radioShowService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RadioShowEditHandler constructor.
     * @param RadioShowService $radioShowService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     */
    public function __construct(
        RadioShowService $radioShowService,
        FlashMessageService $alertService,
        LoggerInterface $logger)
    {
        $this->radioShowService = $radioShowService;
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

        $radioShow = $form->getData();

        try{

            if($radioShow->getEndTime()->format("H:i") == '00:00'){
                $currentDate = new \DateTime();
                $currentDate->setTime(23,59,59);
                $radioShow->setEndTime($currentDate);
            }

            $this->radioShowService->update($radioShow);
            $this->alertService->setSuccess(sprintf("You have successfully updated radio show : %s ",$radioShow->getTitle()));

        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst updating radio show , please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}