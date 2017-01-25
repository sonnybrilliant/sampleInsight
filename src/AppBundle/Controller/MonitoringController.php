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

use AppBundle\Entity\RadioStationStream;
use AppBundle\Form\Type\RadioStationStream\RadioStationStreamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MonitoringController extends Controller
{

    /**
     * @Route("/secured/monitor/list" , name="monitor_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.radio_station_stream');
        $datatable->buildDatatable();

        $this->get('session')->remove('dataTableRadioStationId');

        return $this->render('monitor/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Live stream monitor',
            'breadcrumb' => 'Live',
            'action' => 'monitor_list'
        ));
    }

    /**
     * @Route("/secured/monitor/list/results" , name="monitor_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.radio_station_stream');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if($this->get('session')->get('dataTableRadioStationId')){
            $radioStation = $this->get('app.service.radio_station')->getById($this->get('session')->get('dataTableRadioStationId'));
            if($radioStation){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("radioStation = :radio");
                $qb->setParameter('radio',$radioStation);

                $query->setQuery($qb);
                //unset session var
                return $query->getResponse(false);
            }
        }
        return $query->getResponse();
    }


    /**
     * @Route("/secured/monitor/stream/record/{id}" , name="monitor_profile" , options={"expose"=true})
     * @ParamConverter("radioStationStream", class="AppBundle\Entity\RadioStationStream")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,RadioStationStream $radioStationStream)
    {
        $form = $this->createForm(RadioStationStreamType::class,$radioStationStream);

        return $this->render('monitor/profile.html.twig', array(
            'radioStationStream' => $radioStationStream,
            'page_header' => 'Stream #id'.$radioStationStream->getId(),
            'breadcrumb' => 'View',
            'action' => 'monitor_profile',
            'form' => $form->createView()
        ));
    }


}
