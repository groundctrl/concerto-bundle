<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM\Repository;

use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;

/**
 * Class ConcertoEntityRepository
 *
 * Repository for implementers of SoloistAwareInterface.
 */
class ConcertoEntityRepository extends EntityRepository
{
    /**
     * Finds an Entity by its identifier, returns it as a SoloistAwareFacade.
     *
     * @param mixed $id                  The search criteria.
     * @param int   $lockMode            The LockMode.
     * @param mixed $lockVersion         The LockVersion.
     * @return SoloistAwareFacade|null   The found entity wrapped in a SoloistAwareFacade, or null.
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        $entity = $this->_em->find($this->_class->rootEntityName, $id, $lockMode, $lockVersion);

        return $entity === null ?
            null
          : new SoloistAwareFacade($entity, $this->_class);
    }
} 