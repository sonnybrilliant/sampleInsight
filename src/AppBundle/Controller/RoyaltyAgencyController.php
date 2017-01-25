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

use AppBundle\Entity\RoyaltyAgency;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class RoyaltyAgencyController extends Controller
{

    /**
     * @Route("/secured/royalty/agency/list" , name="royalty_agency_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.royalty_agency');
        $datatable->buildDatatable();

        return $this->render('royalty_agency/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Royalty agencies list',
            'breadcrumb' => 'list',
            'action' => 'royalty_agency_list'
        ));
    }

    /**
     * @Route("/secured/royalty/agency/list/results" , name="royalty_agency_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.royalty_agency');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }


    /**
     * @Route("/secured/royalty/agency/profile/{slug}" , name="royalty_agency_profile" , options={"expose"=true})
     * @ParamConverter("royaltyAgency", class="AppBundle\Entity\RoyaltyAgency")
     * @Method("GET")
     * @Cache(lastModified="royaltyAgency.getUpdatedAt()")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,RoyaltyAgency $royaltyAgency)
    {
        return $this->render('royalty_agency/profile.html.twig', array(
            'royaltyAgency' => $royaltyAgency,
            'page_header' => $royaltyAgency->getName()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'royalty_agency_profile'
        ));
    }


}
