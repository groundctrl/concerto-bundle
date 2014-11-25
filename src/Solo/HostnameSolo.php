<?php

namespace Ctrl\Bundle\ConcertoBundle\Solo;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HostnameSolo
 *
 * Strategy for determining Soloist from
 * the SERVER_NAME of your request.
 */
class HostnameSolo implements SoloInterface
{
    /** @var string */
    protected $soloistField;

    /** @var EntityRepository */
    protected $repository;

    /**
     * Creates a new HostnameSolo
     *
     * @param EntityRepository $repo   The Repository to do the lookup.
     * @param string           $sField The Entity's field to look at
     */
    public function __construct(EntityRepository $repo, $sField)
    {
        $this->repository = $repo;
        $this->soloistField = $sField;
    }

    /**
     * Finds the Entity whose $this->soloistField matches SERVER_NAME.
     *
     * @param  Request $request The Request coming in.
     *
     * @return Soloist          The found Soloist.
     *
     * @throws \UnexpectedValueException when if a non-Soloist is found.
     * @throws \BadMethodCallException   on failure.
     */
    public function getSoloist(Request $request)
    {
        $hostName = $request->getHost();
        $ret = $this->repository->findOneBy( [ $this->soloistField => $hostName ] );
        if($ret !== null && is_a( $ret, 'Ctrl\Bundle\ConcertoBundle\Model\Soloist' ) ) {
            return $ret;
        }

        if($ret !== null) {

            throw new \UnexpectedValueException("The entity found does not extend Soloist: " . get_class($ret));
        }

        return null;
    }
}
