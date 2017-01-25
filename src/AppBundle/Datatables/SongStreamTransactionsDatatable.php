<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class SongStreamTransactionsDatatable
 *
 * @package AppBundle\Datatables
 */
class SongStreamTransactionsDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function ($line) {

            $routeRadioSation = $this->router->generate('radio_station_profile', array('slug' => $line['radioStation']['slug']));

            if(isset($line['artistObject'])){
                $routeArtist = $this->router->generate('artist_profile', array('slug' => $line['artistObject']['slug']));
                $line['artistObject']['title']= '<a href="'.$routeArtist.'" alt="'.$line['artistObject']['title'].'" title="'.$line['artistObject']['title'].'">'.ucfirst($this->truncate($line['artistObject']['title'],FileUtil::LENGTH)).'</a>';
            }

            if(isset($line['song'])){
                $routeSong = $this->router->generate('song_profile', array('slug' => $line['song']['slug']));
                $line['title']= '<a href="'.$routeSong.'" alt="'.$line['song']['title'].'" title="'.$line['song']['title'].'">'.ucfirst($this->truncate($line['song']['title'],FileUtil::LENGTH)).'</a>';
            }elseif (isset($line['title'])){
                $line['title'] = $this->truncate($line['title'],FileUtil::LENGTH);
            }

            if (isset($line['duration'])) {
                $hours = floor($line['duration'] / 3600);
                $minutes = floor($line['duration'] % 3600 / 60);
                $seconds = $line['duration'] % 60;
                $line['duration'] = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);;
            }

            $line['radioStation']['name'] = '<a href="' . $routeRadioSation . '" alt="'.$line['radioStation']['name'].'" title="'.$line['radioStation']['name'].'">' . ucfirst($line['radioStation']['name']) . '</a>';

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {

        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false,
            'delay' => 0,
            'extensions' => array(),
            'highlight' => false,
            'highlight_color' => 'red'
        ));

        if(isset($options['songId'])){
            $this->ajax->set(array(
                'url' => $this->router->generate('song_transaction_list_results',array('songId' => $options['songId'])),
                'type' => 'GET',
                'pipeline' => 0
            ));
        }else{
            $this->ajax->set(array(
                'url' => $this->router->generate('song_transaction_list_results'),
                'type' => 'GET',
                'pipeline' => 0
            ));
        }



        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'desc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(''),
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => false,
            'row_id' => 'id'
        ));
        

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => '#Id',
            ))
            ->add('title', 'column', array(
                'title' => "<i class='fa fa-music'></i>&nbsp;Song",
            ))
            ->add('song.title', 'column', array(
                'visible' => false,
            ))
            ->add('song.slug', 'column', array(
                'visible' => false,
            ))

            ->add('artistObject.title', 'column', array(
                'visible' => true,
                'title' => "<i class='fa fa-user'></i>&nbsp;Artist",
            ))
            ->add('artistObject.slug', 'column', array('visible' => false))
            ->add('duration', 'column', array(
                'title' => "<i class='fa fa-clock-o'></i>&nbsp;Duration"
            ))
            ->add('radioStation.name', 'column', array(
                'title' => "<i class='fa fa-volume-up'></i>&nbsp;Station"
            ))
            ->add('song.title', 'column', array('visible' => false))
            ->add('radioStation.slug', 'column', array('visible' => false))
            ->add('playedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Played At",
                'date_format' => 'llll',
            ))
            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i>&nbsp;Actions",
                'actions' => array(
                    array(
                        'route' => 'monitor_profile',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'View',
                        'icon' => 'fa fa-eye',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Show more details',
                            'class' => 'btn btn-default btn-rad btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\RadioStationStream';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'song_stream_transactions_datatable';
    }
}
