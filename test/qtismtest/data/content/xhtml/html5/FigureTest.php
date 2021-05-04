<?php

namespace qtismtest\data\content\xhtml\html5;

use qtism\data\content\FlowCollection;
use qtism\data\content\xhtml\html5\FigCaption;
use qtism\data\content\xhtml\html5\Figure;
use qtism\data\content\xhtml\text\P;
use qtismtest\QtiSmTestCase;

class FigureTest extends QtiSmTestCase
{
    public function testSetContentWithUniqueFigCaption(): void
    {
        $p = new P();
        $caption1 = new FigCaption('caption1');
        $caption2 = new FigCaption('caption2');

        $subject = new Figure();
        $content = new FlowCollection([$p, $caption1]);

        $subject->setContent($content);

        self::assertEquals(new FlowCollection([$p]), $subject->getContent());
        self::assertSame($caption1, $subject->getFigCaption());
        self::assertEquals($content, $subject->getComponents());

        $subject->setFigCaption($caption2);

        self::assertEquals(new FlowCollection([$p]), $subject->getContent());
        self::assertSame($caption2, $subject->getFigCaption());
        self::assertEquals(
            new FlowCollection([$p, $caption2]),
            $subject->getComponents()
        );
    }

    public function testGetQtiClassName(): void
    {
        $subject = new Figure();

        self::assertEquals('figure', $subject->getQtiClassName());
    }
}
