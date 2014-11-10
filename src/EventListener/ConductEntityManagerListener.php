<?php

namespace Ctrl\Bundle\ConcertoBundle\EventListener;

use Ctrl\Bundle\ConcertoBundle\Event\SoloEvent;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ConductEntityManagerListener
 *
 * When a soloist.found event is dispatched, set
 * our Conductor's Soloist to be what was found.
 */
class ConductEntityManagerListener
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
        $this->em->setSoloist($event->getSoloist());
    }
}