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

use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\RadioShow\RadioShowTypeService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RadioShowTypeCreateHandler
{
    /**
     * @var RadioShowTypeService
     */
    private $radioShowTypeService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SongCreateHandler constructor.
     * @param RadioShowTypeService $radioShowTypeService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     */
    public function __construct(
        RadioShowTypeService $radioShowTypeService,
        FlashMessageService $alertService,
        LoggerInterface $logger)
    {
        $this->radioShowTypeService = $radioShowTypeService;
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

        $radioShowType = $form->getData();

        try{

            $this->radioShowTypeService->create($radioShowType);
            $this->alertService->setSuccess(sprintf("You have successfully added radio show type: %s ",$radioShowType->getTitle()));

        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst adding radio show type, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}