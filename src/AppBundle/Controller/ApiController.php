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
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @Route("api/v1/monitoring/callback",name="api_broadcast_monitoring")
     * @param Request $request
     * @return Response
     */
    public function broadcastAction(Request $request)
    {
        $this->get('logger')->error("==================================START");
        $this->get('logger')->error(print_r($request->request->all(),true));
        $this->get('logger')->error("==================================END");
        $this->get('app.handler_api.radio_station_stream_create')->handle($request);
        return new Response('done');
    }
}
