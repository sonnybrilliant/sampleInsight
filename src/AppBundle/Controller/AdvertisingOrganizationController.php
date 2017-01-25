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

use AppBundle\Entity\AdvertisingOrganization;
use AppBundle\Form\Type\AdvertisingOrganization\AdvertisingOrganizationCreateType;
use AppBundle\Form\Type\AdvertisingOrganization\AdvertisingOrganizationProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class AdvertisingOrganizationController extends Controller
{

    /**
     * @Route("/secured/advertising/organization/list" , name="advertising_organization_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.advertising_organization');
        $datatable->buildDatatable();

        return $this->render('adverts/advertising_organization_list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Advertising organization list',
            'breadcrumb' => 'list',
            'action' => 'advertising_organization_list'
        ));
    }

    /**
     * @Route("/secured/advertising/organization/list/results" , name="advertising_organization_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.advertising_organization');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * @Route("/secured/advertising/organization/profile/{slug}" , name="advertising_organization_profile", options={"expose"=true})
     * @ParamConverter("advertisingOrganization", class="AppBundle\Entity\AdvertisingOrganization")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function profileAction(Request $request,AdvertisingOrganization $advertisingOrganization)
    {
        $form = $this->createForm(AdvertisingOrganizationProfileType::class,$advertisingOrganization);

        return $this->render('adverts/advertising_organization_profile.html.twig', array(
            'form' => $form->createView(),
            'page_header' => $advertisingOrganization->getName()."'s profile",
            'breadcrumb' => 'Profile',
            'advertisingOrganization' => $advertisingOrganization,
            'action' => 'advertising_organization_profile'
        ));
    }

    /**
     * @Route("/secured/advertising/organization/add" , name="advertising_organization_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.advertising_organization_create");
        $advertisingOrganization = new AdvertisingOrganization();
        $form = $this->createForm(AdvertisingOrganizationCreateType::class,$advertisingOrganization);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('advertising_organization_list');
        }

        return $this->render('adverts/advertising_organization_create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add advertising organization',
            'breadcrumb' => 'Add',
            'action' => 'advertising_organization_add'
        ));
    }

    /**
     * @Route("/secured/advertising/organization/edit/{slug}" , name="advertising_organization_edit", options={"expose"=true})
     * @ParamConverter("advertisingOrganization", class="AppBundle\Entity\AdvertisingOrganization")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function editAction(Request $request,AdvertisingOrganization $advertisingOrganization)
    {
        $service = $this->get("app.handler_form.advertising_organization_edit");
        $form = $this->createForm(AdvertisingOrganizationCreateType::class,$advertisingOrganization);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('advertising_organization_list');
        }

        return $this->render('adverts/advertising_organization_edit.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Edit advertising organization',
            'breadcrumb' => 'Edit',
            'advertisingOrganization' => $advertisingOrganization,
            'action' => 'advertising_organization_edit'
        ));
    }

}
