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

class ArchiveController extends Controller
{

    /**
     * @param $radioStationId = null
     * @Route("/secured/archive/transaction/list/results/{radioStationId}" , name="archive_transaction_list_results",defaults={"radioStationId":null})
     * @return Response
     */
    public function transactionListResultsAction($radioStationId = null)
    {
        $datatable = $this->get('app.datatable.radio_station_archives');
        $datatable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

        if(!is_null($radioStationId)){
            $radioStation = $this->get('app.service.radio_station')->getById($radioStationId);
            if($radioStation){
                $query->buildQuery();

                $qb = $query->getQuery();
                $qb->andWhere("radioStation = :radio");
                $qb->setParameter('radio',$radioStation);

                $query->setQuery($qb);
                return $query->getResponse(false);
            }

        }
        return $query->getResponse();
    }


}