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

use AppBundle\Common\FileUtil;
use AppBundle\Entity\Advert;
use AppBundle\Form\Type\Advert\AdvertCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdvertController extends Controller
{

    /**
     * @Route("/secured/advert/list" , name="advert_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.advert');
        $datatable->buildDatatable();

        return $this->render('adverts/advert_list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Advert list',
            'breadcrumb' => 'list',
            'action' => 'advert_list'
        ));
    }

    /**
     * @Route("/secured/advert/list/results" , name="advert_list_results", options={"expose"=true})
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.advert');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        return $query->getResponse();
    }

    /**
     * @param $advertId
     * @Route("/secured/advert/transaction/list/results/{advertId}" , name="advert_transaction_list_results",defaults={"advertId":null})
     * @return Response
     */
    public function transactionListResultsAction($advertId = null)
    {
        $datatable = $this->get('app.datatable.advert_stream');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($advertId)){
            $advert = $this->get('app.service.advert')->getById($advertId);
            if($advert){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("advert = :advert");
                $qb->setParameter('advert',$advert);

                $query->setQuery($qb);
                return $query->getResponse(false);
            }

        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/adverts/profile/{slug}/{range}" , name="advert_profile", options={"expose"=true},defaults={"range":"this-week"})
     * @ParamConverter("advert", class="AppBundle\Entity\Advert")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function profileAction(Request $request,Advert $advert,$range = 'this-week')
    {
        $advertDatatable = $this->get('app.datatable.advert_stream');
        $advertDatatable->buildDatatable(array('advertId'=>$advert->getId()));

        $arrPlays = null;
        $isDefaultView = false;

        if($range == 'last-week'){
            $arrPlays = $this->get('app.service.radio_station_stream')->getAdvertLastWeekRadioStationPlays($advert->getId());
        }else if($range == 'current-month'){
            $arrPlays = $this->get('app.service.radio_station_stream')->getAdvertCurrentMonthRadioStationPlays($advert->getId());
        }else if($range == 'last-month') {
            $arrPlays = $this->get('app.service.radio_station_stream')->getAdvertLastMonthRadioStationPlays($advert->getId());
        }else if($range == 'historical'){
            $arrPlays = $this->get('app.service.radio_station_stream')->getAdvertHistoricalRadioStationPlays($advert->getId());
        }else{
            $isDefaultView = true;
            $arrPlays = $this->get('app.service.radio_station_stream')->getAdvertWeekRadioStationPlays($advert->getId());
        }

        $totalPlaysBy = FileUtil::sumArrayCountArtistSong($arrPlays);
        $chartData = $this->get('app.service.radio_station_stream')->processEchartsDataForSongTopRadioStationPlays($arrPlays);

        $totalPlays = FileUtil::sumArrayCountArtistSong($this->get('app.service.radio_station_stream')->getAdvertHistoricalRadioStationPlays($advert->getId()));


        return $this->render('adverts/advert_profile.html.twig', array(
            'advert' => $advert,
            'page_header' => '#Ad "'.$advert->getTitle().'" profile',
            'breadcrumb' => 'Profile',
            'action' => 'advert_profile',
            'advertDatatable' => $advertDatatable,
            'echartsData' => $chartData,
            'isDefaultView' => $isDefaultView,
            'statsRange' => $range,
            'totalPlaysBy' => $totalPlaysBy,
            'totalPlays' => $totalPlays
        ));
    }

    /**
     * @Route("/secured/advert/add" , name="advert_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.advert_create");
        $advert = new Advert();
        $form = $this->createForm(AdvertCreateType::class,$advert);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('advert_list');
        }

        return $this->render('adverts/advert_create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add advert',
            'breadcrumb' => 'Add',
            'action' => 'advert_add'
        ));
    }
}