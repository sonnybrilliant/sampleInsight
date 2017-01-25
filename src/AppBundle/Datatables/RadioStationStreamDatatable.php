<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class RadioStationStreamDatatable
 *
 * @package AppBundle\Datatables
 */
class RadioStationStreamDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function ($line) {

            if($line['promo']){
                $routePromo = $this->router->generate('promo_profile', array('slug' => $line['promo']['slug']));
                $line['title'] = '<a href="' . $routePromo . '" alt="'.$line['promo']['title'].'" title="'.$line['promo']['title'].'">' . ucfirst($this->truncate($line['promo']['title'], FileUtil::LENGTH)) . '</a>';
            }elseif($line['slogan']){
                $routeSlogan = $this->router->generate('slogan_profile', array('slug' => $line['slogan']['slug']));
                $line['title'] = '<a href="' . $routeSlogan . '" alt="'.$line['slogan']['title'].'" title="'.$line['slogan']['title'].'">' . ucfirst($this->truncate($line['slogan']['title'], FileUtil::LENGTH)) . '</a>';
            }else if (isset($line['advert'])) {
                $routeAdvert = $this->router->generate('advert_profile', array('slug' => $line['advert']['slug']));
                $line['title'] = '<a href="' . $routeAdvert . '" alt="'.$line['advert']['title'].'" title="'.$line['advert']['title'].'">' . ucfirst($this->truncate($line['advert']['title'], FileUtil::LENGTH)) . '</a>';
            } elseif (isset($line['song'])) {
                $routeSong = $this->router->generate('song_profile', array('slug' => $line['song']['slug']));
                $line['title'] = '<a href="' . $routeSong . '" alt="'.$line['song']['title'].'" title="'.$line['song']['title'].'">' . ucfirst($this->truncate($line['song']['title'], FileUtil::LENGTH)) . '</a>';
            } else if (isset($line['title'])) {
                if (strlen($line['title']) > FileUtil::LENGTH) {
                    $line['title'] = $this->truncate($line['title'], FileUtil::LENGTH);
                }
            }

            if (isset($line['advertisingOrganization'])) {
                $routeOrganization = $this->router->generate('advertising_organization_profile', array('slug' => $line['advertisingOrganization']['slug']));
                $strOrganizationName = $this->truncate($line['advertisingOrganization']['name'], FileUtil::LENGTH);
                $line['artist'] = '<a href="' . $routeOrganization . '" alt="'.$line['advertisingOrganization']['name'].'" title="'.$line['advertisingOrganization']['name'].'">' . ucfirst($strOrganizationName) . '</a>';

            } elseif (isset($line['artistObject'])) {
                $routeArtist = $this->router->generate('artist_profile', array('slug' => $line['artistObject']['slug']));
                $line['artist'] = '<a href="' . $routeArtist . '" alt="'.$line['artistObject']['title'].'" title="'.$line['artistObject']['title'].'">' . ucfirst($this->truncate($line['artistObject']['title'],FileUtil::LENGTH)) . '</a>';
            } elseif (isset($line['artist'])) {
                $line['artist'] = $this->truncate($line['artist'], FileUtil::LENGTH);
            }

            if (isset($line['duration'])) {
                $hours = floor($line['duration'] / 3600);
                $minutes = floor($line['duration'] % 3600 / 60);
                $seconds = $line['duration'] % 60;
                $line['duration'] = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);;

            }

            if (isset($line['contentType'])) {
                $str = '';
                if($line['contentType']['title'] == 'promo'){
                   $str = 'tags';
                }elseif ($line['contentType']['title'] == 'music'){
                    $str = 'music';
                }elseif ($line['contentType']['title'] == 'advert'){
                    $str = 'money';
                }elseif ($line['contentType']['title'] == 'slogan'){
                    $str = 'bullhorn';
                }

                $line['contentType']['title'] = '<i class="fa fa-'.$str.'"></i> '.ucfirst($line['contentType']['title']);
            }

            $routeRadioStation = $this->router->generate('radio_station_profile', array('slug' => $line['radioStation']['slug']));
            $line['radioStation']['name'] = '<a href="' . $routeRadioStation . '" alt="View radio station profile" title="View radio station profile">' . ucfirst($line['radioStation']['name']) . '</a>';

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
            'url' => $this->router->generate('monitor_list_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

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
            'individual_filtering_position' => 'foot',
            'use_integration_options' => true,
            'force_dom' => false,
            'row_id' => 'id'
        ));


        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => '#Id',
            ))
            ->add('title', 'column', array(
                'title' => "<i class='fa fa-file-audio-o'></i>&nbsp;Content",
            ))
            ->add('song.title', 'column', array(
                'visible' => false,
            ))
            ->add('song.slug', 'column', array(
                'visible' => false,
            ))
            ->add('advertisingOrganization.name', 'column', array(
                'visible' => false,
            ))
            ->add('advertisingOrganization.slug', 'column', array(
                'visible' => false,
            ))
            ->add('advert.title', 'column', array(
                'visible' => false,
            ))
            ->add('advert.slug', 'column', array(
                'visible' => false,
            ))
            ->add('slogan.title', 'column', array(
                'visible' => false,
            ))
            ->add('slogan.slug', 'column', array(
                'visible' => false,
            ))
            ->add('promo.title', 'column', array(
                'visible' => false,
            ))
            ->add('promo.slug', 'column', array(
                'visible' => false,
            ))
            ->add('artistObject.title', 'column', array(
                'visible' => false,
            ))
            ->add('artistObject.slug', 'column', array(
                'visible' => false,
            ))
            ->add('artist', 'column', array(
                'title' => "<i class='fa fa-user'></i> Author",
            ))
            ->add('contentType.title', 'column', array(
                'title' => "<i class='fa fa-folder'></i>&nbsp;Type",
                'width' => '50',
                'searchable' => false
            ))
            ->add('duration', 'column', array(
                'title' => "<i class='fa fa-clock-o'></i>&nbsp;Duration",
                'width' => '80',
                'searchable' => false
            ))
            ->add('radioStation.name', 'column', array(
                'title' => "<i class='fa fa-volume-up'></i>&nbsp;Station",
            ))
            ->add('radioStation.slug', 'column', array('visible' => false))
            ->add('playedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Played At",
                'date_format' => 'llll',
                'searchable' => false
            ))
            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i> Actions",
                'width' => '70',
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
                            'title' => $this->translator->trans('datatables.actions.show'),
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
        return 'radio_station_stream_datatable';
    }
}
