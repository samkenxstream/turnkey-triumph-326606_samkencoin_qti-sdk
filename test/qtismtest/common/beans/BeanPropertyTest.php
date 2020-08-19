<?php

namespace qtismtest\common\beans;

use qtism\common\beans\BeanException;
use qtism\common\beans\BeanProperty;
use qtismtest\common\beans\mocks\SimpleBean;
use qtismtest\QtiSmTestCase;
use stdClass;

class BeanPropertyTest extends QtiSmTestCase
{
    public function testNoProperty()
    {
        $this->setExpectedException(
            BeanException::class,
            "The class property with name 'prop' does not exist in class 'stdClass'.",
            BeanException::NO_PROPERTY
        );

        $beanProperty = new BeanProperty(stdClass::class, 'prop');
    }

    public function testPropertyNotAnnotated()
    {
        $this->setExpectedException(
            BeanException::class,
            "The property with name 'anotherUselessProperty' for class '" . \qtismtest\common\beans\mocks\SimpleBean::class . "' is not annotated.",
            BeanException::NO_PROPERTY
        );

        $beanProperty = new BeanProperty(SimpleBean::class, 'anotherUselessProperty');
    }
}
