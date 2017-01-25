<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */
namespace AppBundle\Service\Core;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessageService
{
    private  $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    private function setMessage($type,$message)
    {
        $this->session->getFlashBag()->add($type,$message);
    }

    public function setSuccess($message)
    {
        $this->setMessage('success',$message);
    }

    public function setError($message)
    {
        $this->setMessage('error',$message);
    }

    public function setInfo($message)
    {
        $this->setMessage('info',$message);
    }

    public function setWarn($message)
    {
        $this->setMessage('warn',$message);
    }
}