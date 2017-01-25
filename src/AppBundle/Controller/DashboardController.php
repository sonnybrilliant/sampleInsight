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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DashboardController extends Controller
{
    /**
     * @Route("/secured/dashboard/chart/top/songs/{range}" , name="dashboard_home",defaults={"range":"this-week"})
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function chartsTopSongsAction(Request $request,$range = 'this-week')
    {
         $arrTopWeekSongsCurrentWeek = $this->get('app.service.radio_station_stream')->getDashboardTopSongs($range);

        return $this->render('dashboard/charts_top_songs.html.twig', array(
            'action' => 'dashboard_home',
            'arrTopWeekSongs' => $arrTopWeekSongsCurrentWeek,
            //'arrTopWeekArtists' => $this->get('app.service.radio_station_stream')->processEchartsDataForTopArtist($arrTopWeekArtistsCurrentWeek),
            'page_header' => 'Top songs',
            'breadcrumb' => 'Charts',
            'statsRange' => $range
        ));
    }

    /**
     * @Route("/secured/dashboard/chart/top/artist/{range}" , name="dashboard_charts_artists",defaults={"range":"this-week"})
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function chartsToWeekArtistsAction(Request $request,$range = 'this-week')
    {
        $arrTopArtistsCurrentWeek = $this->get('app.service.radio_station_stream')->getDashboardTopArtists($range);

        return $this->render('dashboard/charts_top_artists.html.twig', array(
            'action' => 'dashboard_home_charts_artists',
            'arrTopWeekArtists' => $this->get('app.service.radio_station_stream')->processEchartsDataForTopArtist($arrTopArtistsCurrentWeek),
            'page_header' => 'Top artist',
            'breadcrumb' => 'Charts',
            'statsRange' => $range
        ));
    }

    /**
     * Redirect users to their respective homes bases on type of user
     *
     * @Route("/secured/home" , name="my_home")
     * @param Request $request
     * @Method("GET")
     * @return Response
     */
    public function homeAction(Request $request)
    {
        $user = $this->getUser();
        $url = null;

        if(($user->getIsRadioStationCompiler()) || ($user->getIsRadioStationAdmin())){
            $url = $this->generateUrl('radio_station_profile',array('slug'=>$user->getRadioStation()->getSlug()));
        }else{
            $url = $this->generateUrl('dashboard_home');
        }

        return $this->redirect($url);
    }
}
