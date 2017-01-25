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
namespace AppBundle\Handler\Form\AdvertisingOrganization;

use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\AdvertisingOrganization\AdvertisingOrganizationService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;

class AdvertisingOrganizationEditHandler
{
    /**
     * @var AdvertisingOrganizationService
     */
    private $advertisingOrganizationService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;



    /**
     * AdvertisingOrganizationCreateHandler constructor.
     *
     * @param AdvertisingOrganizationService $advertisingOrganizationService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     */
    public function __construct(
        AdvertisingOrganizationService $advertisingOrganizationService,
        FlashMessageService $alertService,
        LoggerInterface $logger)
    {
        $this->advertisingOrganizationService = $advertisingOrganizationService;
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

        $advertisingOrganization = $form->getData();

        try{
            $this->advertisingOrganizationService->update($advertisingOrganization);
            $this->alertService->setSuccess(sprintf("You have successfully updated organization: %s ",$advertisingOrganization->getName()));
        }catch (\Exception $e){
            $this->alertService->setError("Oops something went wrong whilst updating organization, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}