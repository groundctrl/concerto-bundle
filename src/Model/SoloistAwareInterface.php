<?php

namespace Ctrl\Bundle\ConcertoBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interface SoloistAwareInterface
 *
 * To be implemented by entities which have a direct association with the Soloist.
 */
interface SoloistAwareInterface
{
    /** @param Soloist $soloist */
    public function setSoloist(Soloist $soloist);
}
