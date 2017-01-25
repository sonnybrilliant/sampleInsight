<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class AdvertMonitoredDatatable
 *
 * @package AppBundle\Datatables
 */
class AdvertMonitoredDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function ($line) {

            $routeRadioSation = $this->router->generate('radio_station_profile', array('slug' => $line['radioStation']['slug']));

            $line['radioStation']['name'] = '<a href="' . $routeRadioSation . '" alt="View radio station profile" title="View radio station profile">' . ucfirst($line['radioStation']['name']) . '</a>';

            if (isset($line['duration'])) {
                $hours = floor($line['duration'] / 3600);
                $minutes = floor($line['duration'] % 3600 / 60);
                $seconds = $line['duration'] % 60;
                $line['duration'] = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);;

            }

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

        if(isset($options['advertId'])){
            $this->ajax->set(array(
                'url' => $this->router->generate('advert_transaction_list_results',array(
                    'advertId' => $options['advertId']
                )),
                'type' => 'GET',
                'pipeline' => 0
            ));
        }else{
            $this->ajax->set(array(
                'url' => $this->router->generate('advert_transaction_list_results'),
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
                'title' => "<i class='fa fa-money'></i>&nbsp;Advert",
            ))


            ->add('advert.title', 'column', array('visible' => false))
            ->add('advert.slug', 'column', array('visible' => false))

            ->add('duration', 'column', array(
                'title' => "<i class='fa fa-clock-o'></i>&nbsp;Duration"
            ))
            ->add('radioStation.name', 'column', array(
                'title' => "<i class='fa fa-volume-up'></i>&nbsp;Station"
            ))

            ->add('radioStation.slug', 'column', array('visible' => false))
            ->add('playedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Played At",
                'date_format' => 'llll',
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
        return 'advert_stream_transactions_datatable';
    }
}
