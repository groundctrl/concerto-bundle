<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM\Repository;

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
     *
     * If the entity's metadata lists a custom repository, return that after checking compatibility.
     * If not, and it implements SoloistAwareInterface, return a ConcertoEntityRepository.
     * Otherwise return Doctrine's default EntityRepository.
     *
     * @param EntityManagerInterface $c          The Entity Manager.
     * @param string                 $entityName The class name of the entity.
     *
     * @throws \UnexpectedValueException
     * @return \Doctrine\Common\Persistence\ObjectRepository The requested Repository.
     */
    protected function createRepository( EntityManagerInterface $c, $entityName )
    {
        $metaData = $c->getClassMetadata($entityName);
        $customRCN = $metaData->customRepositoryClassName;

        $entityIsSoloistAware = $metaData->getReflectionClass()
            ->implementsInterface('Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface')
        ;

        $customRepoIsConcertoRepo = is_null($customRCN) ?
              null
            : (new \ReflectionClass($customRCN))
                ->isSubclassOf('Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository')
        ;

        if ( isset($customRCN) ) {
            if ( ! ( $entityIsSoloistAware xor $customRepoIsConcertoRepo ) ) { // ! (A xor B) = "both or neither"
                //We've been told what repo to use, and errythang's cool
                $repository = new $customRCN( $c, $metaData );

            } else {                                                             // TRUE FALSE || FALSE TRUE
                //We've been told what repo to use, but there's a problem
                throw new \UnexpectedValueException("The repository requested for "
                    . ($entityIsSoloistAware ? "SoloistAware entity " : "") . $entityName
                    . " could not be made because " . $customRCN
                    . ($customRepoIsConcertoRepo ? " is a " : " is not a ")
                    . "ConcertoEntityRepository."
                );
            }
        } elseif ($entityIsSoloistAware) {
            //we can infer what repo to use
            $repository = new ConcertoEntityRepository( $c, $metaData );

        } else {
            //what?
            $repository = new EntityRepository( $c, $metaData );
        }

        return $repository;
    }
}
