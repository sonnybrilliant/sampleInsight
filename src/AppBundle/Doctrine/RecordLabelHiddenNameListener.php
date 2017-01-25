<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */
namespace AppBundle\Doctrine;

use AppBundle\Entity\RecordLabel;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Hidden name is used mainly when creating record label via stream processing(Prevent duplication)
 *
 * Class RecordLabelHiddenNameListener
 * @package AppBundle\Doctrine
 */
class RecordLabelHiddenNameListener implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof RecordLabel) {
            return;
        }

        //set name
        $entity->setHiddenName($entity->getName());
    }

    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }
}