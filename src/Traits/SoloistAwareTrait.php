<?php

namespace Ctrl\Bundle\ConcertoBundle\Traits;

use Doctrine\Common\PropertyChangedListener;

/**
 * Class SoloistAwareTrait
 *
 * A trait to minimize user involvement in setting up the Bundle.
 * If you add `use SoloistAwareTrait;` to the entities which need
 * to be persisted with Soloist, set its change-tracking policy
 * to "NOTIFY", and the Repository holding those entities is or
 * extends ConcertoEntityRepository, then everything should just
 * work.
 *
 * To make your own implementation, do not use this trait but keep
 * the change-tracking policy and Repository as per above. You would
 * then need implement your own _onPropertyChanged logic.
 */
trait SoloistAwareTrait
{
    /** @var PropertyChangedListener[] */
    protected $_concerto_listeners = [];

    /**
     * Adds a PropertyChangedListener to the Entity
     *
     * @param PropertyChangedListener $listener The listener to add.
     */
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
        $this->_concerto_listeners[] = $listener;
    }

    /**
     * Notifies listeners that a property changed.
     * Checks backtrace to ensure it was called from the right place.
     *
     * @param string $propName The name of the property.
     * @param mixed $oldValue  The value the property was.
     * @param mixed $newValue  The value the property is changing to.
     *
     * @throws \BadMethodCallException when called from anywhere but a SoloistAwareFacade.
     */
    public function _onPropertyChanged($propName, $oldValue, $newValue)
    {
        $caller = debug_backtrace(false)[2]['class'];

        if($caller != 'Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade') {
            throw new \BadMethodCallException("Caller of _onPropertyChanged must be a SoloistAwareFacade. Got: "
                                 . $caller);
        }

        if ($this->_concerto_listeners) {
            foreach ($this->_concerto_listeners as $listener) {
                $listener->propertyChanged($this, $propName, $oldValue, $newValue);
            }
        }
    }

} 