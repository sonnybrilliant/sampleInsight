<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class AdvertisingOrganizationDatatable
 *
 * @package AppBundle\Datatables
 */
class AdvertisingOrganizationDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){

            $line['name'] = $this->truncate($line['name'],FileUtil::LENGTH);

            return $line;
        };

        return $formatter;
    }

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
        return $this->authorizationChecker->isGranted('ROLE_ADVERT_EDIT');
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
            'url' => $this->router->generate('advertising_organization_list_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10,25, 50, 100),
            'order_classes' => true,
            'order' => array(array(2, 'desc')),
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
            ->add('name', 'column', array(
                'title' => "<i class='fa fa-building'></i> Organization",
            ))

            ->add('slug', 'column', array('visible'=>false))
            ->add('createdBy.slug', 'column', array('visible' => false))
            ->add('createdBy.firstName', 'column', array('visible' => false,'searchable'=>true))
            ->add('createdBy.lastName', 'column', array('visible' => false,'searchable'=>true))
            ->add('lastActiveAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar-o'></i>&nbsp;Last activity At",
                'date_format' => 'llll',
            ))
            ->add('createdAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Created At",
                'date_format' => 'llll',
            ))

            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i>&nbsp;Actions",
                'actions' => array(
                    array(
                        'route' => 'advertising_organization_profile',
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
                        'route' => 'advertising_organization_edit',
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
        return 'AppBundle\Entity\AdvertisingOrganization';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'advertising_organization_datatable';
    }
}
