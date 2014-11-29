<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Model;


use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareFacade;
use Ctrl\Bundle\ConcertoBundle\Tests\ConcertoTestCase;

class SoloistAwareFacadeTest extends ConcertoTestCase
{
    private $wrapped;
    private $meta;
    private $sut;

    function setUp()
    {
        $this->wrapped = $this->mock(
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity', null);

        $em = $this->createTestConductor();
        $this->meta = $em->getClassMetadata(
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity');

        $this->sut = new SoloistAwareFacade($this->wrapped, $this->meta);
    }

    function getMethodParameters()
    {
        $class = new \ReflectionClass('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity');

        $methods = [];
        foreach ($class->getMethods() as $method) {

            if(array_search($method->getName(), ['__construct', 'getSubject', '_onPropertyChanged', 'addPropertyChangedListener']) === false) {

                if ($method->getNumberOfRequiredParameters() === 0) {
                    $methods[] = array($method->getName(), array());
                } elseif ($method->getNumberOfRequiredParameters() > 0) {
                    $methods[] = array($method->getName(), array_fill(0, $method->getNumberOfRequiredParameters(), 'req') ?: array());
                }
                if ($method->getNumberOfParameters() != $method->getNumberOfRequiredParameters()) {
                    $methods[] = array($method->getName(), array_fill(0, $method->getNumberOfParameters(), null) ?: array());
                }
            }
        }

        return $methods;
    }

    function getPublicProperties()
    {
        $class = new \ReflectionClass('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity');
        $props = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop)
        {
            $props[] = [$prop];
        }

        return $props;
    }

    function getNonPublicProperties()
    {
        $class = new \ReflectionClass('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity');
        $props = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE) as $prop)
        {
            $props[] = [$prop];
        }

        return $props;
    }

    function testGetSubjectReturnsWrappedInstance()
    {
        $this->assertInstanceOf(
            'Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity\ConcertoTestAwareEntity',
            $this->sut->getSubject()
        );
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage You cannot call _onPropertyChanged directly
     */
    function testOnPropertyChangedErrorsWhenCalledManually()
    {
        $this->sut->_onPropertyChanged(0, 0, 0);
    }

    /**
     * @param string $method     The name of the method to call.
     * @param array  $parameters Its arguments.
     *
     * @dataProvider getMethodParameters
     */
    function testAllOtherMethodCallsAreDelegatedToTheWrappedInstance($method, array $parameters)
    {
        $stub = $this->wrapped
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue('INNER VALUE FROM ' . $method));

        call_user_func_array(array($stub, 'with'), $parameters);

        $this->assertSame('INNER VALUE FROM ' . $method, call_user_func_array(array($this->sut, $method), $parameters));
    }

    /**
     * @param \ReflectionProperty $prop The property to test.
     *
     * @dataProvider getPublicProperties
     */
    function testAllPublicPropertyCallsAreDelegatedToTheWrappedInstance($prop)
    {
        $propName = $prop->getName();
        $this->assertSame($this->wrapped->$propName, $this->sut->$propName);
    }
}