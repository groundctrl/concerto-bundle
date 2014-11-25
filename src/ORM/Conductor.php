<?php

namespace Ctrl\Bundle\ConcertoBundle\ORM;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Ctrl\Bundle\ConcertoBundle\ORM\Repository\ConcertoEntityRepositoryFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

/**
 * Class Conductor
 *
 * Concerto's EntityManager. When a persistence-related
 * method is called, the Conductor simply removes the
 * SoloistAwareFacade around the Entity and calls the
 * same method through parent::methodName().
 *
 * So Conductor::$method( $facade = $arg1, $arg2, ... ) returns
 * EntityManager::$method( $facade->getSubject(), $arg2, ... )
 */
class Conductor extends EntityManager
{
    /** @var Soloist */
    protected $soloist;

    /** @var string */
    protected $soloistClassName;


    /**
     * Factory method to create Conductor instances.
     *
     * @param mixed         $conn         An array with the connection parameters or an existing Connection instance.
     * @param Configuration $config       The Configuration instance to use.
     * @param EventManager  $eventManager The EventManager instance to use.
     *
     * @return Conductor The created Conductor.
     *
     * @throws \InvalidArgumentException
     * @throws ORMException
     */
    public static function create($conn,  Configuration $config, EventManager $eventManager = null)
    {

        if ( ! $config->getMetadataDriverImpl() ) {
            throw ORMException::missingMappingDriverImpl();
        }

        if ( is_array( $conn ) ) {
            $eventManager = $eventManager ?: new EventManager();
            $conn = DriverManager::getConnection( $conn, $config, $eventManager );

        } elseif ( $conn instanceof Connection ) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        $config->setRepositoryFactory(new ConcertoEntityRepositoryFactory());
        $config->addFilter('soloist', 'Ctrl\Bundle\ConcertoBundle\ORM\Filter\SoloistFilter');

        return new self($conn, $config, $eventManager == null ? new EventManager() : $eventManager);
    }

    /**
     * AKA "The Tenant"
     *
     * @return Soloist
     */
    public function getSoloist()
    {
        return $this->soloist;
    }

    /**
     * Returns the classname of the whatever you
     * decided to be the Soloist.
     *
     * @return string
     */
    public function getSoloistClassName()
    {
        return $this->soloist;
    }

    /**
     * Sets the Soloist.
     *
     * @param  Soloist $soloist The Soloist to set
     */
    public function setSoloist($soloist)
    {
        if( $this->soloist === $soloist ) {
            return;
        }
        $this->soloist = $soloist;
    }

    /**
     * Sets the classname of whatever you
     * decided to be the Soloist.
     *
     * @param string $className The name of the class.
     */
    public function setSoloistClassName( $className )
    {
        $this->soloistClassName = $className;
    }

    /** {@inheritdoc} */
    public function  persist($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function   remove($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function  refresh($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function   detach($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function    merge($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function contains($entity) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function    flush($entity = null) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args()); }
    /** {@inheritdoc} */
    public function     copy($entity, $deep = false) { return $this->callWithoutWrapper(__FUNCTION__, func_get_args());}

    /**
     * Maps any SoloistAwareFacade in $args to SAF::getSubject(),
     * otherwise $args remains unchanged.
     * Then calls $methodName($args) as a regular EntityManager
     *
     * @param  string $methodName Name of the method to call.
     * @param  mixed  $args       Arguments for $methodName.
     * @return mixed  The result of calling EntityManager::$methodName(unwrapped $args)
     */
    private function callWithoutWrapper($methodName, $args)
    {
        $unwrapper = function($x)
                     {
                         if(is_a($x, 'Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade')) {
                             return $x->getSubject();
                         }
                         return $x;
                     };

        $args = array_map($unwrapper, $args);

        //the following is faster than a straight call_user_func_array()
        switch(count($args))
        {
            case 0:
                return parent::$methodName();
            case 1:
                return parent::$methodName($args[0]);
            case 2:
                return parent::$methodName($args[0], $args[1]);
            default:
                return call_user_func_array(['parent', $methodName], $args);
        }
    }
}
