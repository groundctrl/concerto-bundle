<?php

namespace Ctrl\Bundle\ConcertoBundle\Solo;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

/**
 * Interface SoloInterface
 *
 * Interface for defining Solos (Strategies)
 * for finding the Soloist (Tenant).
 */
interface SoloInterface
{
    /**
     * Logic to get from the current Request
     * to a Soloist (Tenant).
     *
     * @param  Request $request
     * @return Soloist
     */
    public function getSoloist(Request $request);
} 