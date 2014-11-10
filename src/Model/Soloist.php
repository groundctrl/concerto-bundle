<?php

namespace Ctrl\Bundle\ConcertoBundle\Model;

/**
 * Interface Soloist
 *
 * AKA "the Tenant."
 * Implement this on whichever class you decide is the tenant.
 */
interface Soloist
{
    /** @return int */
    public function getId();
} 