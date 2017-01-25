<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class ArtistDatatable
 *
 * @package AppBundle\Datatables
 */
class ArtistDatatable extends AbstractDatatableView
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
    public function isAllowedToEditProfile()
    {
        return $this->authorizationChecker->isGranted('ROLE_ARTIST_EDIT');
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){
                 $routeArtist = $this->router->generate('artist_profile', array('slug' => $line['slug']));

            if($line['status']['title'] == 'active' ){
                     $class = 'label-primary';
                 } else {
                     $class = 'label-default';
                 }
                $line['status']['title'] = '<span class="label '.$class.' btn-xs">' . ucfirst($line['status']['title']) . '</span>';
                $line['title']= '<a href="'.$routeArtist.'" alt="'.$line['title'].'" title="'.$line['title'].'">'.ucfirst($this->truncate($line['title'], FileUtil::LENGTH)).'</a>';

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
            'url' => $this->router->generate('artist_list_results'),
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
                'title' => "<i class='fa fa-user'></i> Artist",
            ))
            ->add('status.title', 'column', array(
                'title' => "<i class='fa fa-info-circle'></i>&nbsp;Status",
            ))
            ->add('isLocal', 'boolean', array(
                'title' => 'Local?',
                'true_icon' => 'glyphicon glyphicon-check',
                'false_icon' => 'glyphicon glyphicon-minus-sign',
                'width' => '60'

            ))
            ->add('slug', 'column', array('visible' => false))

            ->add('updatedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Updated At",
                'date_format' => 'llll',
            ))
            ->add('slug', 'column', array('visible' => false))

            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i>&nbsp;Actions",
                'actions' => array(
                    array(
                        'route' => 'artist_profile',
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
                    ),
                    array(
                        'route' => 'artist_edit',
                        'route_parameters' => array(
                            'slug' => 'slug'
                        ),
                        'label' => $this->translator->trans('datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.edit'),
                            'class' => 'btn btn-danger btn-rad btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => function($row) {
                            // caution the line $row['createdBy']['username'] is already formatted in the lineFormatter
                            //if ($row['createdBy']['id'] == $this->getUser()->getId() or true === $this->isAdmin()) {
                            //    return true;
                            //};
                            if($this->isAllowedToEditProfile()){
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
        return 'AppBundle\Entity\Artist';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'artist_datatable';
    }
}
