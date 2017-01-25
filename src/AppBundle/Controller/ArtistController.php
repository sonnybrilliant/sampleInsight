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
use AppBundle\Entity\Artist;
use AppBundle\Form\Type\Artist\ArtistCreateType;
use AppBundle\Form\Type\Artist\ArtistEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class ArtistController extends Controller
{

    /**
     * @Route("/secured/artist/list" , name="artist_list")
     * @param Request $request
     * @Method("GET")
     * @Security("has_role('ROLE_ARTIST_VIEW')")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.artist');
        $datatable->buildDatatable();

        $this->get('session')->remove('dataTableRecordLabelId');

        return $this->render('artist/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Artist list',
            'breadcrumb' => 'List',
            'action' => 'artist_list'
        ));
    }

    /**
     * @Route("/secured/artist/list/results" , name="artist_list_results")
     * @return Response
     */
    public function listResultsAction()
    {
        $datatable = $this->get('app.datatable.artist');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if($this->get('session')->get('dataTableRecordLabelId')){
            $recordLabel = $this->get('app.service.record_label')->getById($this->get('session')->get('dataTableRecordLabelId'));
            if($recordLabel){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("recordLabel = :label");
                $qb->setParameter('label',$recordLabel);

                $query->setQuery($qb);

                return $query->getResponse(false);
            }
        }

        return $query->getResponse();
    }

    /**
     * @param null $artistId
     * @Route("/secured/artist/transaction/list/results/{artistId}" , name="artist_transaction_list_results",defaults={"artistId":null})
     * @return Response
     */
    public function transactionListResultsAction($artistId = null)
    {
        $datatable = $this->get('app.datatable.artist_song_stream_transaction');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($artistId)){
            $artist = $this->get('app.service.artist')->getById($artistId);
            if($artist){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("artistObject = :artist");
                $qb->setParameter('artist',$artist);

                $query->setQuery($qb);
                //unset session var
                return $query->getResponse(false);
            }
        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/artist/add" , name="artist_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.artist_create");
        $artist = new Artist();
        $form = $this->createForm(ArtistCreateType::class,$artist);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('artist_profile',array('slug'=>$artist->getSlug()));
        }

        return $this->render('artist/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add artists',
            'breadcrumb' => 'Add',
            'action' => 'artist_add'
        ));
    }

    /**
     * @Route("/secured/artist/profile/{slug}/{range}" , name="artist_profile" , options={"expose"=true},defaults={"range":"this-week"})
     * @ParamConverter("artist", class="AppBundle\Entity\Artist")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,Artist $artist,$range = 'this-week')
    {

        $this->get('session')->set('dataTableArtistId',$artist->getId());

        $songDatatable = $this->get('app.datatable.artist.song');
        $songDatatable->buildDatatable(array('artistId'=>$artist->getId()));

        $artistDatatable = $this->get('app.datatable.artist_song_stream_transaction');
        $artistDatatable->buildDatatable(array('artistId'=>$artist->getId()));

        $arrTopPlays = null;
        $isDefaultView = false;

        if($range == 'last-week'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getArtistLastWeekTopSongs($artist->getId());
        }else if($range == 'current-month'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getArtistCurrentMonthTopSongsPlays($artist->getId());
        }else if($range == 'last-month') {
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getArtistLastMonthTopSongsPlays($artist->getId());
        }else if($range == 'historical'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getArtistHistoricalTopSongsPlays($artist->getId());
        }else{
            $isDefaultView = true;
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getArtistWeekTopSongs($artist->getId());
        }

        $totalPlaysBy = FileUtil::sumArrayCountArtistSong($arrTopPlays);
        $arrTopPlays = $this->get('app.service.radio_station_stream')->processEchartsDataForSongTopRadioStationPlays($arrTopPlays);


        $totalPlays = FileUtil::sumArrayCountArtistSong($this->get('app.service.radio_station_stream')->getArtistHistoricalTopSongsPlays($artist->getId()));


        return $this->render('artist/profile.html.twig', array(
            'artist' => $artist,
            'page_header' => $artist->getTitle()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'artist_profile',
            'songDatatable' => $songDatatable,
            'artistDatatable' => $artistDatatable,
            'echartsData' => $arrTopPlays,
            'statsRange' => $range,
            'isDefaultView' => $isDefaultView,
            'totalPlays' => $totalPlays,
            'totalPlaysBy' => $totalPlaysBy
        ));
    }

    /**
     * @Route("/secured/artist/edit/{slug}" , name="artist_edit" , options={"expose"=true})
     * @ParamConverter("artist", class="AppBundle\Entity\Artist")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request,Artist $artist)
    {
        $service = $this->get("app.handler_form.artist_edit");
        $form = $this->createForm(ArtistEditType::class,$artist);

        $currentStatus = $artist->getStatus();
        $currentImage = $artist->getArtistImage();
        $form->handleRequest($request);

        if($service->handle($form,$currentStatus,$currentImage)){
            return $this->redirectToRoute('artist_profile',array('slug'=>$artist->getSlug()));
        }

        return $this->render('artist/edit.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Edit '.$artist->getTitle()."'s profile",
            'breadcrumb' => 'Edit',
            'action' => 'artist_edit',
            'artist' => $artist
        ));
    }
}
