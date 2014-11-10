<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM\Repository;

use Ctrl\Bundle\ConcertoBundle\ORM\Conductor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;

/**
 * Class ConcertoEntityRepositoryFactory
 *
 * Creates Entity Repositories for the given entity name.
 */
class ConcertoEntityRepositoryFactory extends DefaultRepositoryFactory
{
    /**
     * Create a new repository instance for an entity class.
     * If the entity's metadata lists a custom repository, return that. If not,
     * and it implements SoloistAwareInterface, return a ConcertoEntityRepository.
     * Otherwise return Doctrine's default EntityRepository.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $c          The Entity Manager.
     * @param string                               $entityName The class name of the entity.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository The requested Repository.
     */
    protected function createRepository( EntityManagerInterface $c, $entityName )
    {
        $concertoRCN = is_a( $c, 'Ctrl\Bundle\ConcertoBundle\ORM\Conductor' ) ?
            $c->getConcertoRepositoryClassName()
            : null;

        $metaData = $c->getClassMetadata($entityName);
        $customRCN = $metaData->customRepositoryClassName;

        if( $customRCN !== null ) {
            //we've been told what repo to use
            $repository = new $customRCN( $c, $metaData );
        } elseif( $concertoRCN ) {
            //we can infer what repo to use
            $repository = new $concertoRCN( $c, $metaData );
        } else {
            //what?
            $repository = new EntityRepository( $c, $metaData );
        }

        return $repository;
    }
}
