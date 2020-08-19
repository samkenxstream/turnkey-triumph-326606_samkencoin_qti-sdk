<?php

namespace qtismtest\data\content\interactions;

use qtism\data\content\BlockStaticCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\interactions\HottextInteraction;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Div;
use qtismtest\QtiSmTestCase;

class HottextInteractionTest extends QtiSmTestCase
{
    public function testCreateNoContent()
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            "A HottextInteraction object must be composed of at least one BlockStatic object, none given."
        );

        new HottextInteraction('RESPONSE', new BlockStaticCollection());
    }

    public function testSetMaxChoicesInvalidValue()
    {
        $div = new Div();
        $div->setContent(new FlowCollection([new TextRun('content...')]));
        $hottextInteraction = new HottextInteraction('RESPONSE', new BlockStaticCollection([$div]));

        $this->setExpectedException(
            \InvalidArgumentException::class,
            "The 'maxChoices' argument must be a positive (>= 0) integer, 'boolean' given."
        );

        $hottextInteraction->setMaxChoices(true);
    }

    public function testSetMinChoicesInvalidValue()
    {
        $div = new Div();
        $div->setContent(new FlowCollection([new TextRun('content...')]));
        $hottextInteraction = new HottextInteraction('RESPONSE', new BlockStaticCollection([$div]));

        $this->setExpectedException(
            \InvalidArgumentException::class,
            "The 'minChoices' argument must be a positive (>= 0) integer value, 'boolean' given."
        );

        $hottextInteraction->setMinChoices(true);
    }

    public function testSetMinChoicesInvalidValueRegardingMaxChoices()
    {
        $div = new Div();
        $div->setContent(new FlowCollection([new TextRun('content...')]));
        $hottextInteraction = new HottextInteraction('RESPONSE', new BlockStaticCollection([$div]));

        $this->setExpectedException(
            \InvalidArgumentException::class,
            "The 'minChoices' argument must respect the limits imposed by 'maxChoices'."
        );

        $hottextInteraction->setMaxChoices(1);
        $hottextInteraction->setMinChoices(2);
    }
}
