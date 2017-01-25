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
use AppBundle\Entity\Song;
use AppBundle\Form\Type\Song\SongCreateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class SongController extends Controller
{

    /**
     * @Route("/secured/song/list" , name="song_list")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
        $datatable = $this->get('app.datatable.song');
        $datatable->buildDatatable();

        $this->get('session')->remove('dataTableArtistId');
        $this->get('session')->remove('dataTableRecordLabelId');

        return $this->render('song/list.html.twig', array(
            'datatable' => $datatable,
            'page_header' => 'Songs list',
            'breadcrumb' => 'List',
            'action' => 'song_list'
        ));
    }

    /**
     * @param $artistId
     * @param $recordLabelId
     * @Route("/secured/song/list/results/{artistId}/{recordLabelId}" , name="song_list_results",defaults={"artistId":null,"recordLabelId":null})
     * @return Response
     */
    public function listResultsAction($artistId = null,$recordLabelId = null)
    {
        $datatable = $this->get('app.datatable.song');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if((!is_null($artistId)) || (!is_null($recordLabelId))){
            if($artistId){
                $artist = $this->get('app.service.artist')->getById($artistId);
                if($artist){
                    $query->buildQuery();

                    $qb = $query->getQuery();
                    $qb->andWhere("artist = :artist");
                    $qb->setParameter('artist',$artist);

                    $query->setQuery($qb);

                    return $query->getResponse(false);
                }
            }elseif($recordLabelId){
                $recordLabel = $this->get('app.service.record_label')->getById($recordLabelId);
                if($recordLabel){
                    $query->buildQuery();

                    $qb = $query->getQuery();
                    $qb->andWhere("recordLabel = :label");
                    $qb->setParameter('label',$recordLabel);

                    $query->setQuery($qb);

                    return $query->getResponse(false);
                }
            }

        }
        return $query->getResponse();
    }

    /**
     * @param null $songId
     * @Route("/secured/song/transaction/list/results/{songId}" , name="song_transaction_list_results",defaults={"songId":null})
     * @return Response
     */
    public function transactionListResultsAction($songId = null)
    {
        $datatable = $this->get('app.datatable.song_stream_transaction');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($songId)){
            $song = $this->get('app.service.song')->getById($songId);
            if($song){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("song = :song");
                $qb->setParameter('song',$song);

                $query->setQuery($qb);
                //unset session var
                return $query->getResponse(false);
            }

        }
        return $query->getResponse();
    }

    /**
     * @Route("/secured/song/add" , name="song_add")
     * @param Request $request
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function createAction(Request $request)
    {
        $service = $this->get("app.handler_form.song_create");
        $song = new Song();
        $form = $this->createForm(SongCreateType::class,$song);

        $form->handleRequest($request);

        if($service->handle($form)){
            return $this->redirectToRoute('song_profile',array('slug'=>$song->getSlug()));
        }

        return $this->render('song/create.html.twig', array(
            'form' => $form->createView(),
            'page_header' => 'Add song',
            'breadcrumb' => 'Add',
            'action' => 'song_add'
        ));
    }

    /**
     * @Route("/secured/song/forward/to/profile/{id}" , name="song_forward_to_profile")
     * @ParamConverter("song", class="AppBundle\Entity\Song")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function forwardToProfileAction(Request $request,Song $song)
    {
        return $this->redirectToRoute('song_profile',array(
            'slug' => $song->getSlug()
        ));
    }

    /**
     * @Route("/secured/song/profile/{slug}/{range}" , name="song_profile" , options={"expose"=true},defaults={"range":"this-week"})
     * @ParamConverter("song", class="AppBundle\Entity\Song")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function profileAction(Request $request,Song $song,$range = 'this-week')
    {
        $songDatatable = $this->get('app.datatable.song_stream_transaction');
        $songDatatable->buildDatatable(array('songId'=>$song->getId()));

        $arrTopPlays = null;
        $isDefaultView = false;

        if($range == 'last-week'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getSongLastWeekTopRadioStationPlays($song->getId());
        }else if($range == 'current-month'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getSongCurrentMonthTopRadioStationPlays($song->getId());
        }else if($range == 'last-month') {
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getSongLastMonthTopRadioStationPlays($song->getId());
        }else if($range == 'historical'){
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getSongHistoricalSongRadioStationPlays($song->getId());
        }else{
            $isDefaultView = true;
            $arrTopPlays = $this->get('app.service.radio_station_stream')->getSongWeekTopRadioStationPlays($song->getId());
        }

        $totalPlaysBy = FileUtil::sumArrayCountArtistSong($arrTopPlays);
        $arrTopPlays = $this->get('app.service.radio_station_stream')->processEchartsDataForSongTopRadioStationPlays($arrTopPlays);

        $totalPlays = FileUtil::sumArrayCountArtistSong($this->get('app.service.radio_station_stream')->getSongHistoricalSongRadioStationPlays($song->getId()));

        return $this->render('song/profile.html.twig', array(
            'song' => $song,
            'page_header' => $song->getTitle()."'s profile",
            'breadcrumb' => 'Profile',
            'action' => 'song_profile',
            'songDatatable' => $songDatatable,
            'echartsData' => $arrTopPlays,
            'statsRange' => $range,
            'isDefaultView' => $isDefaultView,
            'totalPlays' => $totalPlays,
            'totalPlaysBy' => $totalPlaysBy
        ));
    }

    /**
     * @Route("/secured/song/approve/{slug}" , name="song_approve")
     * @ParamConverter("song", class="AppBundle\Entity\Song")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function approveAction(Request $request,Song $song)
    {
        try{
            $this->get("app.service.song")->approveUploadedSong($song);
            $message = "Song ".$song->getTitle().' By '.$song->getArtist()->getTitle().' is now Active, the Uploader will be notified.';
            $this->get("app.alert.service")->setSuccess($message);

            $this->get('app.handler_api.radio_station_incoming_queue_create_handler')->add($song);
        }catch(\Exception $e){
            $this->get('logger')->error('Failed to add song to RadioStationQueue:'.$song->getId());
            $this->get("app.alert.service")->setError("An error occurred whilst adding approving song, please contact admin.");
        }

        return $this->redirect($this->generateUrl('song_profile',array('slug' =>$song->getSlug())));
    }
}
