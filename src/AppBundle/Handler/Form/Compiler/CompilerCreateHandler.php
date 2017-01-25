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
namespace AppBundle\Handler\Form\Compiler;

use AppBundle\Service\Core\FlashMessageService;
use AppBundle\Service\User\CompilerService;
use AppBundle\Service\Core\UserGroupService;
use Symfony\Component\Form\Form;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\User;
use AppBundle\Event\UserEvent;
use AppBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CompilerCreateHandler
{
    /**
     * @var CompilerService
     */
    private $compilerService;

    /**
     * @var FlashMessageService
     */
    private $alertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserGroupService
     */
    private $userGroupService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CompilerCreateHandler constructor.
     * @param CompilerService $compilerService
     * @param FlashMessageService $alertService
     * @param LoggerInterface $logger
     * @param UserGroupService $userGroupService
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        CompilerService $compilerService,
        FlashMessageService $alertService,
        LoggerInterface $logger,
        UserGroupService $userGroupService,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->compilerService = $compilerService;
        $this->alertService = $alertService;
        $this->logger = $logger;
        $this->userGroupService = $userGroupService;
        $this->eventDispatcher = $eventDispatcher;
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

        $compiler = $form->getData();
        $compiler->setUserGroup($this->userGroupService->compiler());
        $password = substr(base_convert(bin2hex(hash('sha256', uniqid(mt_rand(), true), true)), 16, 20), 0, 10);
        $compiler->setPlainPassword($password);

        try{
            $this->compilerService->create($compiler);
            $this->alertService->setSuccess(sprintf("You have successfully added compiler: %s ",$compiler->getFirstName()));

            $this->eventDispatcher->dispatch(
                UserEvents::COMPILER_CREATE,
                new UserEvent($compiler)
            );

        }catch (\Exception $e){
            die($e->getMessage());
            $this->alertService->setError("Oops something went wrong whilst adding artist, please contact admin.");
            $this->logger->error($e->getMessage());
            return false;
        }
        return true;
    }

}