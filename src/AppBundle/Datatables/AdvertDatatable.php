<?php

namespace AppBundle\Datatables;

use AppBundle\Common\FileUtil;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class AdvertDatatable
 *
 * @package AppBundle\Datatables
 */
class AdvertDatatable extends AbstractDatatableView
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
        return $this->authorizationChecker->isGranted('ROLE_ADVERT_EDIT');
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){


            if($line['status']['title'] == 'active' ){
                $class = 'label-primary';
            } else {
                $class = 'label-default';
            }

            $line['status']['title'] = '<span class="label '.$class.' btn-xs">' . ucfirst($line['status']['title']) . '</span>';

            $routeAd =  $this->router->generate('advert_profile', array('slug' => $line['slug']));
            $line['title']= '<a href="'.$routeAd.'" alt="'.$line['title'].'" title="'.$line['title'].'">'.ucfirst($this->truncate($line['title'], FileUtil::LENGTH)).'</a>';

            if(isset($line['advertisingOrganization'])){
                $routeOrg =  $this->router->generate('advertising_organization_profile', array('slug' => $line['advertisingOrganization']['slug']));
                $line['advertisingOrganization']['name']= '<a href="'.$routeOrg.'" alt="'.$line['advertisingOrganization']['name'].'" title="'.$line['advertisingOrganization']['name'].'">'.ucfirst($this->truncate($line['advertisingOrganization']['name'], FileUtil::LENGTH)).'</a>';

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

        $this->ajax->set(array(
            'url' => $this->router->generate('advert_list_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10,25, 50, 100),
            'order_classes' => true,
            'order' => array(array(4, 'desc')),
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
            ->add('slug', 'column', array(
                'visible' => false
            ))
            ->add('advertisingOrganization.name', 'column', array(
                'title' => "<i class='fa fa-building'></i> Organization",
            ))
            ->add('advertisingOrganization.slug', 'column', array(
                'visible' => false
            ))
            ->add('status.title', 'column', array(
                'title' => "<i class='fa fa-info-circle'></i>&nbsp;Status",
            ))
            ->add('slug', 'column', array('visible'=>false))
            ->add('playCount', 'column', array(
                'title' => "<i class='fa fa-calculator'></i> Count",
            ))
            ->add('lastPlayedAt', 'datetime', array(
                'title' => "<i class='fa fa-calendar'></i>&nbsp;Last played At",
                'date_format' => 'llll',
            ))

            ->add(null, 'action', array(
                'title' => "<i class='fa fa-gears'></i>&nbsp;Actions",
                'actions' => array(
                    array(
                        'route' => 'advert_profile',
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
        return 'AppBundle\Entity\Advert';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'advert_datatable';
    }
}
