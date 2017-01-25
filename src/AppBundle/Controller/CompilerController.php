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
use AppBundle\Entity\User;
use AppBundle\Form\Type\User\Compiler\CompilerCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class CompilerController extends Controller
{

    /**
     * @Route("/secured/radio/station/compiler/list" , name="compiler_list")
     * @param Request $request
     * @Method("GET")
     * @Security("has_role('ROLE_COMPILER_VIEW')")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.compiler');
        $datatable->buildDatatable();

        $this->get('session')->remove('dataTableRadioStationId');
        return $this->render('compiler/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Compiler list',
            'breadcrumb' => 'List',
            'action' => 'compiler_list'
        ));
    }

    /**
     * @Route("/secured/radio/station/compiler/results/{radioStationId}" , name="compiler_list_results",defaults={"radioStationId":null})
     * @return Response
     */
    public function listResultsAction($radioStationId = null)
    {
        $datatable = $this->get('app.datatable.compiler');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($radioStationId)) {
            $radioStation = $this->get('app.service.radio_station')->getById($radioStationId);
            if($radioStation){
            $query->buildQuery();

            $qb = $query->getQuery();
            $qb->andWhere("user.isRadioStationCompiler = :compiler");
            $qb->andWhere("user.radioStation = :radio");
            $qb->setParameters(array(
                'compiler'=>true,
                'radio' => $radioStation));
            }
        }else{
            $query->buildQuery();

            $qb = $query->getQuery();
            $qb->andWhere("user.isRadioStationCompiler = :compiler");
            $qb->setParameter('compiler',true);
        }

        $query->setQuery($qb);

        return $query->getResponse(false);
    }

    /**
     * @Route("/secured/radio/station/compiler/add" , name="compiler_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COMPILER_EDIT')")
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.compiler_create");
        $compiler = new User();
        $form = $this->createForm(CompilerCreateType::class,$compiler);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('user_profile',array('slug'=>$compiler->getSlug()));
        }

        return $this->render('compiler/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add compiler',
            'breadcrumb' => 'Add',
            'action' => 'compiler_add',
        ));
    }

}
