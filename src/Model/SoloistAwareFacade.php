<?php

namespace Ctrl\Bundle\ConcertoBundle\Model;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class SoloistAwareFacade
 *
 * The class in which the implementers of SoloistAwareInterface are wrapped.
 * Uses magic methods to proc all calls to the wrapped instance.
 * Determines persisted properties using an AnnotationReader, and, when a method is called that would change one of
 * them, adds an additional call to _onPropertyChanged, as per Doctrine's "Notify" change-tracking policy.
 */
class SoloistAwareFacade
{
    /**
     * @var object The wrapped Entity
     */
    private $subject;

    /**
     * @var ClassMetadata Metadata for $subject
     */
    private $metaData;

    /**
     * @var \ReflectionProperty[] The properties of $this->subject which end up in the database
     */
    private $persistedProperties = [];

    /**
     * Sets up the facade.
     *
     * @param object        $subject The Entity to wrap
     * @param ClassMetadata $metadata The Entity's metadata
     */
    public function __construct($subject, ClassMetadata $metadata)
    {
        $this->subject = $subject;
        $this->metaData = $metadata;
        $this->setPersistedProperties();
    }

    /**
     * Reads in the properties from $this->metaData->reflFields
     * and holds on to those with property annotations.
     *
     * @TODO YAML & XML
     */
    private function setPersistedProperties()
    {
        $reader = new FileCacheReader(
            new AnnotationReader(),
            \sys_get_temp_dir() . '/ConcertoTempCache',
            $debug = false
        );

        $props = [];

        foreach($this->metaData->reflFields as $field)
        {
            if($reader->getPropertyAnnotations($field)) {
                $props[] = $field;
            }
        }

        $this->persistedProperties = $props;
    }

    /**
     * Returns the desired property for $this->subject.
     *
     * @param string $propName the name of the property
     * @throws \InvalidArgumentException
     * @return \ReflectionProperty
     */
    private function getReflProp($propName)
    {
        if(property_exists($this->subject, $propName)) {
        #if(array_search($propName, $this->getPersistedProperties(true)) !== false) {

            $count = -1;
            $stopCount = false;
            return array_filter($this->persistedProperties,
                function ($v) use ($propName, &$count, &$stopCount) {
                    $stopCount || $count++;
                    if($v->name == $propName){ $stopCount = true; return true; }
                    else return false;
                }
            )[$count];
        }

        throw new \InvalidArgumentException('Requested property for reflection does not exist: ' . $propName);
    }

    /**
     * Returns an array of \ReflectionProperty, or that
     * same array mapped to \ReflectionProperty::getName().
     *
     * @param bool $namesOnly Whether or not you want the names only.
     * @return string[]|\ReflectionProperty[]
     */
    private function getPersistedProperties($namesOnly = false)
    {
        return $namesOnly ? array_map(function ($x) { return $x->getName(); }, $this->persistedProperties)
            : $this->persistedProperties;
    }

    /**
     * Returns $this->subject, the wrapped instance.
     *
     * @return object
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * _onPropertyChanged needs to be called on $this->subject, not here.
     * This method prevents the user from calling it manually.
     *
     * @param string $prop The property name
     * @param mixed $oldVal The property's current value
     * @param mixed $newVal The property's changed, new value
     *
     * @throws \BadMethodCallException every time, because you should not call this yourself.
     */
    public function _onPropertyChanged($prop, $oldVal, $newVal)
    {
        throw new \BadMethodCallException("You cannot call _onPropertyChanged directly,"
            . " the SoloistAwareFacade will do it automatically for persisted properties.");
    }

    /**
     * Magic getter, works for public properties only.
     *
     * @param $prop
     * @return mixed
     */
    public function __get($prop)
    {
        return $this->subject->$prop;
    }

    /**
     * Passes method call on to $this->subject.
     *
     * @param string $method The name of the method to be called
     * @param mixed[] $args The arguments for that method
     *
     * @return mixed Whatever that method would've returned
     */
    public function __call($method, $args)
    {

        if(property_exists($this->subject, $method) && count($args) == 0) {
            //this conditional is here for getting things to display with twig

            $getMethod = 'get' . ucfirst($method);  //hope you follow naming conventions


            if(method_exists($this->subject, $getMethod)) {

                return $this->subject->{$getMethod}();
            }


            return $this->__get($method);
        }

        $setOrGet = substr($method, 0, 3);
        $prop = lcfirst(substr($method, 3));

        if(in_array($prop, $this->getPersistedProperties(true)) && $setOrGet === "set"){
            //we have some legit changes going down here folks

            $reflProp = $this->getReflProp($prop);
            $reflProp->setAccessible(true);
            $oldVal = $reflProp->getValue($this->subject);

            //C_U_F_A optimization
            switch(count($args)) {
                case 0:
                    $ret = $this->subject->$method();
                break;
                case 1:
                    $ret = $this->subject->$method($args[0]);
                break;
                case 2:
                    $ret = $this->subject->$method($args[0], $args[1]);
                break;
                case 3:
                    $ret = $this->subject->$method($args[0], $args[1], $args[2]);
                break;
                case 4:
                    $ret = $this->subject->$method($args[0], $args[1], $args[2], $args[3]);
                break;
                default:
                    $ret = call_user_func_array([$this->subject, $method], $args);
                break;
            }


            $newVal = $reflProp->getValue($this->subject);

            $this->subject->_onPropertyChanged($prop, $oldVal, $newVal);

            return $ret;
        }

        return call_user_func_array([$this->subject, $method], $args);
    }

    /**
     * Magic setter. Works for public NON-PERSISTED properties.
     * Errors otherwise. You shouldn't be doing persistence-
     * related stuff without calling a method anyway. Shame on you.
     *
     * @param string $prop The name of the property that will be changed
     * @param mixed $value The property's new value
     * @throws \BadMethodCallException
     */
    public function __set($prop, $value)
    {
        if($this->subject->$prop === $value) {
            return;
        }

        if($this->metaData->reflFields[$prop] && array_search($prop, $this->getPersistedProperties(true)) === true) {

            throw new \BadMethodCallException("Attempted to use __set to persist a property. Class inside " .
            "SoloistAwareFacade: " . get_class($this->subject) . ". Attempted to change prop: " . $prop . ".");
        }

        $this->subject->$prop = $value;
    }
}