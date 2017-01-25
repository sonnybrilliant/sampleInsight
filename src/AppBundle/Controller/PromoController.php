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

use AppBundle\Common\ApiType;
use AppBundle\Common\ContentType;
use AppBundle\Entity\Promo;
use AppBundle\Form\Type\Promo\PromoCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PromoController extends Controller
{

    /**
     * @Route("/secured/promo/list" , name="promo_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.promo_list');
        $datatable->buildDatatable();

        return $this->render('promo/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Promotions list',
            'breadcrumb' => 'list',
            'action' => 'promo_list'
        ));
    }

    /**
     * @Route("/secured/promo/list/results" , name="promo_list_results", options={"expose"=true})
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.promo_list');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * @param $promoId = null
     * @Route("/secured/promo/transaction/list/results/{promoId}" , name="promo_transaction_list_results",defaults={"promoId":null})
     * @return Response
     */
    public function transactionListResultsAction($promoId = null)
    {
        $datatable = $this->get('app.datatable.promo_stream');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($promoId)){
            $promo = $this->get('app.service.promo')->getById($promoId);
            if($promo){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("promo = :promo");
                $qb->setParameter('promo',$promo);

                $query->setQuery($qb);
                return $query->getResponse(false);
            }

        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/promo/profile/{slug}" , name="promo_profile", options={"expose"=true})
     * @ParamConverter("promo", class="AppBundle\Entity\Promo")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function profileAction(Request $request,Promo $promo)
    {
        $promoDatatable = $this->get('app.datatable.promo_stream');
        $promoDatatable->buildDatatable(array('promoId' => $promo->getId()));

        return $this->render('promo/profile.html.twig', array(
            'promo' => $promo,
            'page_header' => '#Promo "'.$promo->getTitle().'" profile',
            'breadcrumb' => 'Profile',
            'action' => 'promo_profile',
            'promoDatatable' => $promoDatatable,
        ));
    }

    /**
     * @Route("/secured/promo/add" , name="promo_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.promo_create");
        $promo = new Promo();
        $form = $this->createForm(PromoCreateType::class,$promo);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('promo_list');
        }

        return $this->render('promo/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add promotion',
            'breadcrumb' => 'Add',
            'action' => 'promo_add'
        ));
    }
}
