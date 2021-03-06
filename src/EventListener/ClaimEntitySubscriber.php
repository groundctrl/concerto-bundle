<?php

namespace Ctrl\Bundle\ConcertoBundle\EventListener;

use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreFlushEventArgs;

/**
 * Class ClaimEntitySubscriber
 *
 * Jumps in during Symfony's kernel.request event. PreFlush happens as changes are persisted.
 * Sets the Soloist on the Entity which is about to be persisted, allowing you to not bother
 * with making sure you set it yourself.
 */
class ClaimEntitySubscriber implements EventSubscriber
{
    /**
     * Returns the events $this subscribes to.
     *
     * @return string[] The subscribed events.
     */
    public function getSubscribedEvents()
    {
        return [ Events::preFlush ];
    }

    /**
     * What to do when it's time to persist data.
     *
     * @param PreFlushEventArgs $args Arriving here from Symfony's EventDispatcher
     *
     * @throws \UnexpectedValueException if no Soloist available.
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $soloist = $em->getSoloist();

        $uow = $em->getUnitOfWork();

        if(isset($soloist)) {

            foreach( array_merge( $uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates() ) as $entity )
            {
                if( $entity instanceof SoloistAwareInterface ) {
                    $entity->setSoloist($soloist);
                }
            }
            #foreach($uow->getScheduledEntityDeletions() as $entity)  No need to set soloist since we're deleting
        } else {

            throw new \UnexpectedValueException("PreFlush: Soloist should be set by now.");
        }
    }
} 