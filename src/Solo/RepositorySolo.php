<?php

namespace Ctrl\Bundle\ConcertoBundle\Solo;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RepositorySolo
 *
 * User configures a Repository and relevant method name to use
 * to find the Soloist.
 *
 * This implementation is very similar to the HostnameSolo,
 * but allows for more extensibility.
 */
class RepositorySolo implements SoloInterface
{
    /** @var EntityRepository */
    protected $repository;

    /** @var string */
    protected $repoMethodName;

    /**
     * Constructs a RepositorySolo.
     *
     * @param EntityRepository $repo
     * @param string           $repoMethodName
     */
    public function __construct(EntityRepository $repo, $repoMethodName)
    {
        $this->repository = $repo;
        $this->repoMethodName = $repoMethodName;
    }

    /**
     * Finds the Soloist by calling $this->repoMethodName on $this->repository
     * (both are set up in the configuration).
     *
     * @param  Request $request The request coming from the FindSoloistListener.
     *
     * @return Soloist The Soloist that was found.
     *
     * @throws \UnexpectedValueException when found Entity is not a Soloist.
     * @throws \BadMethodCallException on failure.
     */
    public function getSoloist(Request $request)
    {
        $hostName = $request->server->get('SERVER_NAME');

        $ret = $this->repository->{$this->repoMethodName}($hostName);

        if($ret != null && is_a( $ret, 'Ctrl\Bundle\ConcertoBundle\Model\Soloist' ) ) {
            return $ret;
        }

        if($ret != null) {

            throw new \UnexpectedValueException("The entity found does not extend Soloist: " . get_class($ret));
        }

        throw new \BadMethodCallException("Could not find a soloist using solo: " . get_class()
            . ". You have configured your " . get_class($this->repository) . " to use method \""
            . $this->repoMethodName . "\", but that ain't workin'.");
    }
}