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

use AppBundle\Entity\RecordLabel;
use AppBundle\Form\Type\RecordLabel\RecordLabelCreateType;
use AppBundle\Form\Type\RecordLabel\RecordLabelEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class RecordLabelController extends Controller
{

    /**
     * @Route("/secured/record/label/list" , name="record_label_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.recordlabel');
        $datatable->buildDatatable();

        return $this->render('record_label/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Record labels list',
            'breadcrumb' => 'List',
            'action' => 'record_label_list'
        ));
    }

    /**
     * @Route("/secured/record/label/list/results" , name="record_label_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.recordlabel');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     *
     * @param $recordLabelId null
     * @Route("/secured/record/label/song/transaction/list/results/{recordLabelId}" , name="record_label_song_transaction_list_results", defaults={"recordLabelId": null})
     * @return Response
     */
    public function transactionListResultsAction($recordLabelId = null)
    {
        $datatable = $this->get('app.datatable.recordlabel.song.streams');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($recordLabelId)){
            $label = $this->get('app.service.record_label')->getById($recordLabelId);
            if($label){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("recordLabel = :label");
                $qb->setParameter('label',$label);

                $query->setQuery($qb);
                return $query->getResponse(false);
            }
        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/record/label/create" , name="record_label_create")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.record_label_create");
        $recordLabel = new RecordLabel();
        $form = $this->createForm(RecordLabelCreateType::class,$recordLabel);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('record_label_list');
        }

        return $this->render('record_label/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add record label',
            'breadcrumb' => 'Add',
            'action' => 'record_label_add'
        ));
    }

    /**
     * @Route("/secured/record/label/profile/{slug}" , name="record_label_profile" , options={"expose"=true})
     * @ParamConverter("recordLabel", class="AppBundle\Entity\RecordLabel")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,RecordLabel $recordLabel)
    {
        $songDatatable = $this->get('app.datatable.recordlabel.songs');
        $songDatatable->buildDatatable(array('recordLabelId'=>$recordLabel->getId()));

        $streamDatatable = $this->get('app.datatable.recordlabel.song.streams');
        $streamDatatable->buildDatatable(array('recordLabelId'=>$recordLabel->getId()));


        $this->get('session')->remove('dataTableArtistId');

        return $this->render('record_label/profile.html.twig', array(
            'recordLabel' => $recordLabel,
            'page_header' => $recordLabel->getName()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'record_label_profile',
            'songDatatable' => $songDatatable,
            'streamDatatable' => $streamDatatable
        ));
    }

    /**
     * @Route("/secured/record/label/edit/{slug}" , name="record_label_edit" , options={"expose"=true})
     * @ParamConverter("recordLabel", class="AppBundle\Entity\RecordLabel")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request,RecordLabel $recordLabel)
    {
        $service = $this->get("app.handler_form.record_label_edit");
        $form = $this->createForm(RecordLabelEditType::class,$recordLabel);
        $currentStatus = $recordLabel->getStatus();
        $form->handleRequest($request);

        if($service->handle($form,$currentStatus)){
            return $this->redirectToRoute('record_label_profile',array('slug'=>$recordLabel->getSlug()));
        }

        return $this->render('record_label/edit.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Edit '.$recordLabel->getName()."'s profile",
            'breadcrumb' => 'Edit',
            'action' => 'record_label_edit',
            'recordLabel' => $recordLabel
        ));
    }
}
