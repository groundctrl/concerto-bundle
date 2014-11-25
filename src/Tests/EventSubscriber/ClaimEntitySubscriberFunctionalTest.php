<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\EventSubscriber;

use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ClaimEntitySubscriberFunctionalTest extends ConcertoWebTestCase
{
    function testItCallsSetSoloistOnSoloistAwareEntitiesOnFlush()
    {

    }
}