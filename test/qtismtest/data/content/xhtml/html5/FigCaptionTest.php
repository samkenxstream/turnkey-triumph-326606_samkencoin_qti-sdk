<?php

namespace qtismtest\data\content\xhtml\html5;

use qtism\data\content\xhtml\html5\FigCaption;
use qtismtest\QtiSmTestCase;

class FigCaptionTest extends QtiSmTestCase
{
    public function testGetQtiClassName(): void
    {
        $subject = new FigCaption();

        self::assertSame('figcaption', $subject->getQtiClassName());
    }
}
