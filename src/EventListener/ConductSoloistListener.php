<?php

namespace Ctrl\Bundle\ConcertoBundle\EventListener;

use Ctrl\Bundle\ConcertoBundle\Event\SoloEvent;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ConductSoloistListener
 *
 * When a soloist.found event is dispatched, set
 * our Conductor's Soloist to be what was found.
 */
class ConductSoloistListener
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $em;

    /**
     * @param EntityManagerInterface $em The Entity Manager.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * What to do when the a Soloist has been found.
     *
     * @param SoloEvent $event
     * @return null
     */
    public function onSoloistFound(SoloEvent $event)
    {
        $soloist = $event->getSoloist();
        $this->em->setSoloist($soloist);


        /** @var \Doctrine\ORM\Query\FilterCollection $filters */
        $filters = $this->em->getFilters();
        if ( ! $filters->isEnabled('soloist')) {
            $filters->enable('soloist');
        }

        $filter = $filters->getFilter('soloist');
        $filter->setParameter("soloist_id", $soloist->getId());
    }
}
