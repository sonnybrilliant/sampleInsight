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

use AppBundle\Entity\RadioShowType;
use AppBundle\Entity\RadioShow;
use AppBundle\Form\Type\RadioShow\RadioShowEditType;
use AppBundle\Form\Type\RadioShow\RadioShowTypeCreateType;
use AppBundle\Form\Type\RadioShow\RadioShowCreateType;
use AppBundle\Handler\Form\RadioShow\RadioShowCreateHandler;
use AppBundle\Handler\Form\RadioShow\RadioShowTypeCreateHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RadioShowController extends Controller
{

    /**
     * @Route("/secured/radio/show/list" , name="radio_show_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.radio_show');
        $datatable->buildDatatable();

        return $this->render('show/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Radio shows list',
            'breadcrumb' => 'list',
            'action' => 'radio_show_list'
        ));
    }

    /**
     * @Route("/secured/radio/show/list/results" , name="radio_show_list_results", options={"expose"=true})
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.radio_show');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     *
     * @param $showId null
     * @Route("/secured/radio/show/transaction/list/song/results/{showId}" , name="radio_show_transaction_list_song_results", defaults={"showId": null})
     * @return Response
     */
    public function transactionListSongResultsAction($showId = null)
    {
        $datatable = $this->get('app.datatable.recordlabel.song.streams');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($showId)){
            $label = $this->get('app.service.radio_show')->getById($showId);
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
     * @Route("/secured/radio/show/add" , name="radio_show_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.radio_show_create");
        $radioShow = new RadioShow();
        $form = $this->createForm(RadioShowCreateType::class,$radioShow);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('radio_show_list');
        }

        return $this->render('show/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add radio show',
            'breadcrumb' => 'Add',
            'action' => 'radio_show_add'
        ));
    }

    /**
     * @Route("/secured/radio/show/edit/{slug}" , name="radio_show_edit" , options={"expose"=true})
     * @ParamConverter("show", class="AppBundle\Entity\RadioShow")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request,RadioShow $radioShow)
    {
        $service = $this->get("app.handler_form.radio_show_edit");
        $form = $this->createForm(RadioShowEditType::class,$radioShow);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('radio_show_profile',array('slug'=>$radioShow->getSlug()));
        }

        return $this->render('show/edit.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Edit '.$radioShow->getTitle()."'s profile",
            'breadcrumb' => 'Edit',
            'action' => 'radio_show_edit',
            'radioShow' => $radioShow
        ));
    }

    /**
     * @Route("/secured/radio/show/profile/{slug}" , name="radio_show_profile", options={"expose"=true})
     * @ParamConverter("radioShow", class="AppBundle\Entity\RadioShow")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function profileAction(Request $request,RadioShow $radioShow)
    {
        //$advertDatatable = $this->get('app.datatable.advert_stream');
        //$advertDatatable->buildDatatable();

        return $this->render('show/profile.html.twig', array(
            'radioShow' => $radioShow,
            'page_header' => $radioShow->getTitle()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'radio_show_profile',
            //'advertDatatable' => $advertDatatable,
        ));
    }

    /**
     * @Route("/secured/radio/show/type/add" , name="radio_show_type_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createTypeAction(Request $request)
    {
        $service = $this->get("app.handler_form.radio_show_type_create");
        $radioShowType = new RadioShowType('');
        $form = $this->createForm(RadioShowTypeCreateType::class,$radioShowType);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('radio_show_list');
        }

        return $this->render('show/create_radio_show_type.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add radio show type',
            'breadcrumb' => 'Add',
            'action' => 'radio_show_type_add'
        ));
    }
}