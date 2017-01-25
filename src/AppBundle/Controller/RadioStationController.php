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

use AppBundle\Entity\RadioStation;
use AppBundle\Entity\RadioStationQueue;
use AppBundle\Entity\RadioStationStream;
use AppBundle\Form\Type\RadioStation\RadioStationSongRejectViewType;
use AppBundle\Form\Type\RadioStationStream\RadioStationStreamType;
use AppBundle\Form\Type\RadioStation\RadioStationSongRejectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\ExpressionLanguage\Expression;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class RadioStationController extends Controller
{

    /**
     * @Route("/secured/radio/station/list" , name="radio_station_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.radio_station');
        $datatable->buildDatatable();

        return $this->render('radio_station/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Radio station list',
            'breadcrumb' => 'list',
            'action' => 'radio_station_list'
        ));
    }

    /**
     * @Route("/secured/radio/station/list/results" , name="radio_station_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.radio_station');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * @param $radioStationId null
     * @Route("/secured/radio/station/profile/results/{radioStationId}" , name="radio_station_profile_results", defaults={"radioStationId":null})
     * @return Response
     */
    public function profileResultsAction($radioStationId = null)
    {
        $datatable = $this->get('app.datatable.radio_station_profile_stream');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($radioStationId)){
            $radioStation = $this->get('app.service.radio_station')->getById($radioStationId);
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
     * @Route("/secured/radio/station/profile/{slug}" , name="radio_station_profile" , options={"expose"=true})
     * @ParamConverter("radioStation", class="AppBundle\Entity\RadioStation")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,RadioStation $radioStation)
    {
        $this->get('session')->set('dataTableRadioStationId',$radioStation->getId());
        $arrTop10Songs  = $this->get('app.service.radio_station_stream')->getWeekTopSongsByRadioStationId($radioStation->getId());
        $arrTop10Artists  = $this->get('app.service.radio_station_stream')->getWeekTopArtistsByRadioStationId($radioStation->getId());

        $streamDatatable = $this->get('app.datatable.radio_station_profile_stream');
        $streamDatatable->buildDatatable(array('radioStationId' => $radioStation->getId()));

        $compilerDatatable = $this->get('app.datatable.compiler');
        $compilerDatatable->buildDatatable(array('radioStationId' => $radioStation->getId()));

        $archivesDatatable = $this->get('app.datatable.radio_station_archives');
        $archivesDatatable->buildDatatable(array('radioStationId' => $radioStation->getId()));

        return $this->render('radio_station/profile.html.twig', array(
            'radioStation' => $radioStation,
            'streamDatatable' => $streamDatatable,
            'compilerDatatable' => $compilerDatatable,
            'archivesDatatable' => $archivesDatatable,
            'page_header' => $radioStation->getName()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'radio_station_profile',
            'arrTop10Songs' => $arrTop10Songs ,
            'echartsData' => $this->get('app.service.radio_station_stream')->processEchartsDataForTopArtist($arrTop10Artists)
        ));
    }

    /**
     * @Route("/secured/radio/station/incoming/queue/list" , name="radio_station_incoming_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function incomingQueueAction(Request $request)
    {
        $datatable = $this->get('app.datatable.radio_station_incoming');
        $datatable->buildDatatable();

        return $this->render('radio_station/incoming.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Radio station incoming queue',
            'breadcrumb' => 'Incoming queue',
            'action' => 'radio_station_list_incoming_queue'
        ));

    }

    /**
     * @Route("/secured/radio/station/incoming/queue/list/results" , name="radio_station_incoming_list_results")
     * @return Response
     */
    public function incomingQueueListResultsAction()
    {
        $datatable = $this->get('app.datatable.radio_station_incoming');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("radio_station_queue.status != :active");
                $qb->setParameter('active',$this->get('app.core.status_service')->active());

        $query->setQuery($qb);

        return $query->getResponse(false);

    }

    /**
     * @Route("/secured/radio/station/incoming/queue/approve/{id}" , name="radio_station_incoming_approve" , options={"expose"=true})
     * @ParamConverter("radioStation", class="AppBundle\Entity\RadioStationQueue")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function incomingQueueApproveAction(Request $request, RadioStationQueue $radioStationQueue)
    {
        $radioStationQueue->setApprovedBy($this->getUser());
        $radioStationQueue->setApprovedAt(new \DateTime());

        $this->get('app.handler_api.radio_station_incoming_queue_approve_handler')->approve($radioStationQueue);

        $message = "You have approved the song ".$radioStationQueue->getSong()->getTitle()." to be added to your bubbling queue.";
        $this->get('app.alert.service')->setSuccess($message);

        return $this->redirect($this->generateUrl('radio_station_incoming_list'));

    }

    /**
     * @Route("/secured/radio/station/incoming/queue/reject/{id}" , name="radio_station_incoming_reject" , options={"expose"=true})
     * @ParamConverter("radioStation", class="AppBundle\Entity\RadioStationQueue")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COMPILER_VIEW')")
     * @param Request $request
     * @return Response
     */
    public function incomingQueueRejectAction(Request $request, RadioStationQueue $radioStationQueue)
    {

        $this->denyAccessUnlessGranted(new Expression(
            '(user and user.getIsRadioStationCompiler()) or (user and user.getIsRadioStationAdmin())'
        ));

        $form = $this->createForm(RadioStationSongRejectType::class,$radioStationQueue);

        $form->handleRequest($request);

        if($form->isValid()){
           $data = $form->getData();
           if($data->getMessage() != NULL){
               if(strlen($data->getMessage()) > 10 ){
                   $service = $this->get('app.handler_api.radio_station_incoming_queue_reject_handler');
                   $data->setRejectedBy($this->getUser());
                   if($service->reject($data)) {
                       $msg = "Song '".$radioStationQueue->getSong()->getTitle()."' was successfully rejected, an email will be sent to the artist to inform them of the status";
                       $this->get('app.alert.service')->setSuccess($msg);
                       return $this->redirectToRoute('radio_station_incoming_list');
                   }
               }else{
                   $notice = "Please provide a message with enough details to help rectify this in future.";
                   $this->get('app.alert.service')->setError($notice);
               }
           }else{
               $notice = "Message cannot be empty";
               $this->get('app.alert.service')->setError($notice);
           }
        }

        //notice
        $notice = "Please state a reason why the song is being rejected, We will communicate the message to the artist to help improve the quality of content sent to you.";
        $this->get('app.alert.service')->setInfo($notice);

        return $this->render('radio_station/song_reject.twig', array(
            'form' => $form->createView(),
            'page_header' => "Reject song '".$radioStationQueue->getSong()->getTitle()." By ".$radioStationQueue->getArtist()->getTitle()."''",
            'breadcrumb' => 'Reject',
            'action' => 'radio_station_song_reject',
            'radioStationQueue' => $radioStationQueue
        ));

    }

    /**
     * @Route("/secured/radio/station/incoming/queue/view/{id}" , name="radio_station_incoming_view" , options={"expose"=true})
     * @ParamConverter("radioStation", class="AppBundle\Entity\RadioStationQueue")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COMPILER_VIEW')")
     * @param Request $request
     * @return Response
     */
    public function incomingQueueViewAction(Request $request, RadioStationQueue $radioStationQueue)
    {

        $form = $this->createForm(RadioStationSongRejectViewType::class,$radioStationQueue);

        $form->handleRequest($request);


        return $this->render('radio_station/song_view_reject.twig', array(
            'form' => $form->createView(),
            'page_header' => "Rejected song '".$radioStationQueue->getSong()->getTitle()." By ".$radioStationQueue->getArtist()->getTitle()."''",
            'breadcrumb' => 'View',
            'action' => 'radio_station_song_reject_view',
            'radioStationQueue' => $radioStationQueue
        ));

    }


}
