<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class SoloistFilter
 *
 * Filter on the Conductor. If we're looking up an entity that implements SoloistAware,
 * i.e. via a ->findAll(), then this will limit the return to be only those matching the current Soloist.
 */
class SoloistFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // Check if the entity implements SoloistAwareInterface and the parameter exists.
        if ("''" === $this->getParameter('soloist_id')) {
            return '';
        } elseif (!$targetEntity->reflClass->implementsInterface('Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface')) {
            return '';
        }

        $columnId = $targetEntity->associationMappings['soloist']['joinColumns'][0]['name'];

        return $targetTableAlias.'.'.$columnId.' = '.$this->getParameter('soloist_id'); // getParameter applies quoting automatically
    }
}
