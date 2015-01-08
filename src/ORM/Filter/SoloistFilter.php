<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
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
		if ( $this->emptyFilterCheck($targetEntity->reflClass) ) {
			return '';
		}

		$columnId = $this->getSoloistColumnId($targetEntity->associationMappings);

		if( $columnId !== null ) {
			return $targetTableAlias.'.'.$columnId.' = '.$this->getParameter('soloist_id');
			// getParameter applies quoting automatically
		}

		throw new MappingException('Entity of class ' . $targetEntity->getName()
			. ' has no associations which implement Soloist.');
	}

	private function emptyFilterCheck(\ReflectionClass $reflClass)
	{
		return (
			$this->getParameter('soloist_id') === "''" ||
			!$reflClass->implementsInterface('Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface')
		);
	}

	private function getSoloistColumnId($mappings)
	{
		return array_reduce($mappings, function($acc, $x){
			return is_a($x['targetEntity'], 'Ctrl\Bundle\ConcertoBundle\Model\Soloist', true)
				?  $x['joinColumns'][0]['name']
				:  $acc;
		});
	}
}
