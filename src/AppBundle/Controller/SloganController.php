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

use AppBundle\Entity\Slogan;
use AppBundle\Form\Type\Slogan\SloganCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SloganController extends Controller
{

    /**
     * @Route("/secured/slogan/list" , name="slogan_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.slogan');
        $datatable->buildDatatable();

        return $this->render('slogan/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Slogan list',
            'breadcrumb' => 'list',
            'action' => 'slogan_list'
        ));
    }

    /**
     * @Route("/secured/slogan/list/results" , name="slogan_list_results", options={"expose"=true})
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.slogan');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * @param $sloganId
     * @Route("/secured/slogan/transaction/list/results/{sloganId}" , name="slogan_transaction_list_results",defaults={"sloganId":null})
     * @return Response
     */
    public function transactionListResultsAction($sloganId = null)
    {
        $datatable = $this->get('app.datatable.slogan_stream');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($sloganId)){
            $slogan = $this->get('app.service.slogan')->getById($sloganId);
            if($slogan){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("slogan = :slogan");
                $qb->setParameter('slogan',$slogan);

                $query->setQuery($qb);
                return $query->getResponse(false);
            }

        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/slogan/profile/{slug}" , name="slogan_profile", options={"expose"=true})
     * @ParamConverter("slogan", class="AppBundle\Entity\Slogan")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function profileAction(Request $request,Slogan $slogan)
    {
        $sloganDatatable = $this->get('app.datatable.slogan_stream');
        $sloganDatatable->buildDatatable(array('sloganId' => $slogan->getId()));

        return $this->render('slogan/profile.html.twig', array(
            'slogan' => $slogan,
            'page_header' => '#Slogan "'.$slogan->getTitle().'" profile',
            'breadcrumb' => 'Profile',
            'action' => 'slogan_profile',
            'sloganDatatable' => $sloganDatatable,
        ));
    }

    /**
     * @Route("/secured/slogan/add" , name="slogan_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.slogan_create");
        $slogan = new Slogan();
        $form = $this->createForm(SloganCreateType::class,$slogan);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('slogan_list');
        }

        return $this->render('slogan/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add slogan',
            'breadcrumb' => 'Add',
            'action' => 'slogan_add'
        ));
    }
}
