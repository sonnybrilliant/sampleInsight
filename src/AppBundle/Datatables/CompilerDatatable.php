<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class CompilerDatatable
 *
 * @package AppBundle\Datatables
 */
class CompilerDatatable extends AbstractDatatableView
{

    /**
     * Get User.
     *
     * @return mixed|null
     */
    private function getUser()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->securityToken->getToken()->getUser();
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isAllowedToViewProfile()
    {
        return $this->authorizationChecker->isGranted('ROLE_COMPILER_VIEW');
    }

    /**
     * @param $id
     * @return string|void
     */
    private function isMe($id)
    {
        if($id == $this->getUser()->getId()){
            return  "(Me)";
        }
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){
            $routeRadioStation = $this->router->generate('radio_station_profile', array('slug' => $line['radioStation']['slug']));
            $routeUser = $this->router->generate('user_profile', array('slug' => $line['slug']));

            if($line['status']['title'] == 'active' ){
                $class = 'label-primary';
            } else {
                $class = 'label-default';
            }
            $line['firstName'] = '<a href="'.$routeUser.'" alt="View profile" title="View profile">'.ucfirst($line['firstName']).' '.$line['lastName'].' '.$this->isMe($line['id']).'</a>';
            $line['status']['title'] = '<span class="label '.$class.' btn-xs">' . ucfirst($line['status']['title']) . '</span>';
            $line['radioStation']['name'] = '<a href="'.$routeRadioStation.'" alt="View radio station profile" title="View radio station profile">'.ucfirst($line['radioStation']['name']).'</a>';

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


        if(isset($options['radioStationId'])){
            $this->ajax->set(array(
                'url' => $this->router->generate('compiler_list_results',array(
                    'radioStationId' => $options['radioStationId']
                )),
                'type' => 'GET',
                'pipeline' => 0
            ));
        }else{
            $this->ajax->set(array(
                'url' => $this->router->generate('compiler_list_results'),
                'type' => 'GET',
                'pipeline' => 0
            ));
        }


        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10,25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 25,
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
            ->add('firstName', 'column', array(
                'title' => "<i class='fa fa-headphones'></i>&nbsp;Compiler",
            ))
            ->add('lastName','column',array('visible'=>false))
            ->add('isRadioStationCompiler','column',array('visible'=>false))

            ->add('slug', 'column', array('visible'=>false))

            ->add('radioStation.name', 'column', array(
                'title' => "<i class='fa fa-volume-up'></i>&nbsp;Station"
            ))
            ->add('radioStation.slug','column',array('visible'=>false))

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
                        'route' => 'user_profile',
                        'route_parameters' => array(
                            'slug' => 'slug'
                        ),
                        'label' => 'View',
                        'icon' => 'fa fa-eye',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'View profile',
                            'class' => 'btn btn-default btn-rad btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => function($row) {
                            if($this->isAllowedToViewProfile()){
                                return true;
                            }


                            return false;
                        },
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
        return 'AppBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'compiler_datatable';
    }
}
