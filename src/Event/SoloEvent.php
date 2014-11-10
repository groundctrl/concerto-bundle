<?php

namespace Ctrl\Bundle\ConcertoBundle\Event;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class SoloEvent
 *
 * This class is for Symfony's EventDispatcher and our Concerto EventListeners.
 */
class SoloEvent extends Event
{
    /**
     * The found Soloist, or null.
     *
     * @var  Soloist
     */
    protected $soloist;

    /**
     * The event we're reacting to.
     *
     * @var  GetResponseEvent
     */
    protected $event;

    /**
     * Constructs a new SoloEvent.
     *
     * @param Soloist          $soloist
     * @param GetResponseEvent $event
     */
    public function __construct(Soloist $soloist = null, GetResponseEvent $event = null)
    {
        $this->soloist =  $soloist;

        $this->event = $event;
    }

    /**
     * Get the event's Soloist.
     *
     * @return Soloist
     */
    public function getSoloist()
    {
        return $this->soloist;
    }

    /**
     * Set the event's Soloist
     *
     * @param $soloist
     * @return null
     */
    public function setSoloist($soloist)
    {
        if($this->soloist === $soloist) {
            return;
        } else {
            $this->soloist = $soloist;
        }
    }
}
