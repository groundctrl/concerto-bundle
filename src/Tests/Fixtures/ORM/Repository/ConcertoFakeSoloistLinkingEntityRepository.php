<?php


namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository;

use Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepository;
use Doctrine\ORM\Mapping as ORM;

class ConcertoTestSoloistLinkingEntityRepository extends ConcertoEntityRepository
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
