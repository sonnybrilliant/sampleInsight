<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class SongDatatable
 *
 * @package AppBundle\Datatables
 */
class SongDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){
            $routeRecordLabel = $this->router->generate('record_label_profile', array('slug' => $line['recordLabel']['slug']));
            $routeArtist = $this->router->generate('artist_profile', array('slug' => $line['artist']['slug']));
            $routeSong = $this->router->generate('song_profile', array('slug' => $line['slug']));

            if($line['status']['title'] == 'active' ){
                $class = 'label-primary';
            } else {
                $class = 'label-default';
            }
            $line['status']['title'] = '<span class="label '.$class.' btn-xs">' . ucfirst($line['status']['title']) . '</span>';
            $line['artist']['title'] = '<a href="'.$routeArtist.'" alt="'.$line['artist']['title'].'" title="'.$line['artist']['title'].'">'.ucfirst($this->truncate($line['artist']['title'],FileUtil::LENGTH)).'</a>';
            $line['recordLabel']['name'] = '<a href="'.$routeRecordLabel.'" alt="'.$line['recordLabel']['name'].'" title="'.$line['recordLabel']['name'].'">'.ucfirst($this->truncate($line['recordLabel']['name'],FileUtil::LENGTH)).'</a>';
            $line['title'] = '<a href="'.$routeSong.'" alt="'.$line['title'].'" title="'.$line['title'].'">'.ucfirst($this->truncate($line['title'],FileUtil::LENGTH)).'</a>';

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

        $this->ajax->set(array(
            'url' => $this->router->generate('song_list_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10,25, 50, 100),
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
                'title' => "<i class='fa fa-music'></i>&nbsp;Title",
            ))
            ->add('slug', 'column', array('visible'=>false))
            ->add('artist.slug', 'column', array('visible'=>false))
            ->add('recordLabel.slug', 'column', array('visible'=>false))
            ->add('artist.title', 'column', array(
                'title' => "<i class='fa fa-user'></i> Artist",
            ))
            ->add('recordLabel.name', 'column', array(
                'title' => "<i class='fa fa-building-o'></i>&nbsp;Label",
            ))
            ->add('status.title', 'column', array(
                'title' => "<i class='fa fa-info-circle'></i>&nbsp;Status",
            ))
            ->add('updatedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Updated At",
                'date_format' => 'llll',
            ))

            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i>&nbsp;Actions",
                'actions' => array(
                    array(
                        'route' => 'song_profile',
                        'route_parameters' => array(
                            'slug' => 'slug'
                        ),
                        'label' => 'View',
                        'icon' => 'fa fa-eye',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'View',
                            'class' => 'btn btn-default btn-rad btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Song';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'song_datatable';
    }
}
