<?php

namespace Ctrl\Bundle\ConcertoBundle\EventListener;

use Ctrl\Bundle\ConcertoBundle\Event\SoloEvent;
use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Ctrl\Bundle\ConcertoBundle\SoloEvents;
use Ctrl\Bundle\ConcertoBundle\Solo\SoloInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class FindSoloistListener
{
    /** @var    ContainerInterface */
    protected $container;

    /** @var    EventDispatcherInterface */
    protected $dispatcher;

    /** @var SoloInterface */
    protected $solo;

    /** @var Soloist */
    protected $soloist;

    /**
     * Constructs a new FindSoloistListener.
     *
     * @param ContainerInterface       $container  The Symfony Container.
     * @param EventDispatcherInterface $dispatcher The EventDispatcher.
     * @param SoloInterface            $solo       The Solo to use for finding Soloists.
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $dispatcher, SoloInterface $solo)
    {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
        $this->solo = $solo;
    }

    /**
     * Symfony has a kernel.event method-- this gets called
     * (before persistence) when that event is dispatched.
     * Sets the Soloist parameter on the Container.
     *
     * @param GetResponseEvent $event The Event containing
     * the request we'll need to find the Soloist.
     */
    public function onEarlyKernelRequest(GetResponseEvent $event)
    {
        $soloist = $this->solo->getSoloist($event->getRequest());

        if(!$soloist) {
            $sEvent = $this->dispatcher->dispatch(SoloEvents::SOLOIST_NOT_FOUND, new SoloEvent(null, $event));
            $soloist = $sEvent->getSoloist();
            if(!$soloist) {
                return;
            }
        }
        $this->setSoloist($soloist);

        $this->dispatcher->dispatch(SoloEvents::SOLOIST_FOUND, new SoloEvent($soloist, $event));
        $this->container->set('concerto.soloist', $soloist);

    }

    /**
     * Sets the Soloist.
     *
     * @param Soloist $s The Soloist.
     */
    public function setSoloist(Soloist $s)
    {
        if($this->soloist === $s) {
            return;
        }
        $this->soloist = $s;
    }

    /**
     * Gets the Soloist.
     *
     * @return Soloist
     */
    public function getSoloist()
    {
        return $this->soloist;
    }
}
