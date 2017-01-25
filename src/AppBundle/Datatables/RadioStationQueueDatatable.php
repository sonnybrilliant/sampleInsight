<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class RadioStationQueueDatatable
 *
 * @package AppBundle\Datatables
 */
class RadioStationQueueDatatable extends AbstractDatatableView
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
     * @param $radioStationId
     * @param $isApproved
     * @param $isRejected
     * @return bool
     */
    private function isAllowedToApprove($radioStationId,$isApproved,$isRejected)
    {
        $user = $this->getUser();

        if($isRejected || $isApproved){
            return false;
        }else{
            if(($user->getIsRadioStationCompiler()) || ($user->getIsRadioStationAdmin())){
                if($radioStationId == $user->getRadioStation()->getId()){
                    return true;
                }
            }

        }

        return false;
    }

    /**
     * @return bool
     */
    private function canPerfomAction()
    {
        $user = $this->getUser();

        if(($user->getIsRadioStationCompiler()) || ($user->getIsRadioStationAdmin())){
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {

        $formatter = function($line){
            $routeRadioStation = $this->router->generate('radio_station_profile', array('slug' => $line['radioStation']['slug']));
            $routeSong = $this->router->generate('song_profile', array('slug' => $line['song']['slug']));
            $routeArtist = $this->router->generate('artist_profile', array('slug' => $line['artist']['slug']));

            if($line['status']['title'] == 'active' ) {
                $class = 'label-primary';
            } else if($line['status']['title'] == 'rejected'){
                $class = 'label-danger';
            } else {
                $class = 'label-default';
            }
            $line['status']['title'] = '<span class="label '.$class.' btn-xs">' . ucfirst($line['status']['title']) . '</span>';
            $line['radioStation']['name'] = '<a href="'.$routeRadioStation.'" alt="View radio station profile" title="View radio station profile">'.ucfirst($line['radioStation']['name']).'</a>';
            $line['song']['title'] = '<a href="'.$routeSong.'" alt="View song profile" title="View song profile">'.ucfirst($line['song']['title']).'</a>';
            $line['artist']['title'] = '<a href="'.$routeArtist.'" alt="View artist profile" title="View artist profile">'.ucfirst($line['artist']['title']).'</a>';

            if(!$this->canPerfomAction()){
                $routeLabel = $this->router->generate('record_label_profile', array('slug' => $line['recordLabel']['slug']));
                $line['recordLabel']['name'] = '<a href="'.$routeLabel.'" alt="View label profile" title="View label profile">'.ucfirst($line['recordLabel']['name']).'</a>';
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
            'url' => $this->router->generate('radio_station_incoming_list_results'),
            'type' => 'GET',
            'pipeline' => 0
        ));

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
                'title' => 'Id',
            ))
            ->add('isApproved','column',array('visible' => false))
            ->add('isRejected','column',array('visible' => false))
            ->add('song.title', 'column', array(
                'title' => 'Song',
            ))
            ->add('song.slug','column',array('visible' => false))

            ->add('artist.title', 'column', array(
                'title' => 'Artist',
            ))
            ->add('artist.slug','column',array('visible' => false))

            ->add('radioStation.name', 'column', array(
                'title' => 'Radio station',
            ))
            ->add('radioStation.slug','column',array('visible' => false))
            ->add('radioStation.id','column',array('visible' => false))
            ->add('recordLabel.slug','column',array('visible' => false))
            ->add('recordLabel.name','column',array(
                'title' => 'Label',
                'add_if' => function() {
                    return (!$this->canPerfomAction());
                },

            ))
            ->add('status.title', 'column', array(
                'title' => 'Status',
            ))
            ->add('status.code','column',array('visible' => false))
            ->add('updatedAt', 'datetime', array(
                'title' => 'Queued At',
                'date_format' => 'llll',
            ))

            ->add(null, 'action', array(
                'add_if' => function() {
                    return ($this->canPerfomAction());
                },
                'title'   => $this->translator->trans('datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'radio_station_incoming_approve',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Approve',
                        'icon' => 'fa fa-thumbs-o-up',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => "Approve song",
                            'class' => 'btn btn-primary btn-rad btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => function($row) {
                            if($this->isAllowedToApprove($row['radioStation']['id'],$row['isApproved'],$row['isRejected'])){
                                return true;
                            }


                            return false;
                        },
                    ),
                    array(
                        'route' => 'radio_station_incoming_reject',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'confirm' => true,
                        'confirm_message' => 'Are you sure?',
                        'label' => 'Reject',
                        'icon' => 'fa fa-thumbs-o-down',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Reject song',
                            'class' => 'btn btn-danger btn-rad btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => function($row) {
                            // caution the line $row['createdBy']['username'] is already formatted in the lineFormatter
                            //if ($row['createdBy']['id'] == $this->getUser()->getId() or true === $this->isAdmin()) {
                            //    return true;
                            //};
                            if($this->isAllowedToApprove($row['radioStation']['id'],$row['isApproved'],$row['isRejected'])){
                                return true;
                            }


                            return false;
                        },

                    ),
                    array(
                        'route' => 'radio_station_incoming_view',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'View',
                        'icon' => 'fa fa-eye',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'View why song was rejected',
                            'class' => 'btn btn-danger btn-rad btn-xs',
                            'role' => 'button'
                        ),
                        'render_if' => function($row) {
                            // caution the line $row['createdBy']['username'] is already formatted in the lineFormatter
                            //if ($row['createdBy']['id'] == $this->getUser()->getId() or true === $this->isAdmin()) {
                            //    return true;
                            //};
                            if($row['isRejected']){
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
        return 'AppBundle\Entity\RadioStationQueue';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'radio_station_queue_datatable';
    }
}
