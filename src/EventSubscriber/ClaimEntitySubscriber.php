<?php

namespace Ctrl\Bundle\ConcertoBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Class ClaimEntitySubscriber
 *
 * Jumps in during Symfony's kernel.request event. OnFlush happens as changes are persisted.
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
        return array('onFlush');
    }

    /**
     * What to do when it's time to persist data.
     *
     * @param OnFlushEventArgs $args Arriving here from Symfony's EventDispatcher
     *
     * @throws \UnexpectedValueException if no Soloist available.
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $soloist = $em->getSoloist();

        $uow = $em->getUnitOfWork();

        if(isset($soloist)) {

            foreach($uow->getScheduledEntityInsertions() as $entity)
            {
                $entity->setSoloist($soloist);
            }
            foreach($uow->getScheduledEntityUpdates() as $entity)
            {
                $entity->setSoloist($soloist);
            }

            #foreach($uow->getScheduledEntityDeletions() as $entity)  No need to set soloist since we're deleting

        } else {

            throw new \UnexpectedValueException("OnFlush: Soloist should be set by now.");
        }
    }
} 