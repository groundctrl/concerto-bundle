<?php


namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class ConcertoTestSoloistLinkingEntityRepository extends EntityRepository
{
    public function findClientByDomain($request)
    {
        $domain = $request->getHost();

        $em    = $this->getEntityManager();
        $query = $em->createQuery('SELECT l,s FROM CtrlConcertoBundle:ConcertoTestSoloist s JOIN s.linkers l WHERE l.domain = :domain');

        $query->setParameter('domain', $domain);;

        return $query->getOneOrNullResult();
    }
}
