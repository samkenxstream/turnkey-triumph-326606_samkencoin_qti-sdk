<?php

namespace qtismtest\data\content\xhtml\html5;

use qtism\data\content\enums\Role;
use qtism\data\content\FlowCollection;
use qtism\data\content\xhtml\A;
use qtism\data\content\xhtml\html5\FigCaption;
use qtism\data\content\xhtml\text\Br;
use qtism\data\content\xhtml\text\P;

class FigCaptionTest extends Html5ElementTest
{
    public function testCreateWithValues(): void
    {
        $title = 'a title';
        $role = 'contentinfo';
        $id = 'the_id';
        $class = 'css class';
        $lang = 'en';
        $label = 'This is the label.';

        $subject = new FigCaption($title, $role, $id, $class, $lang, $label);

        self::assertSame($title, $subject->getTitle());
        self::assertSame($role, Role::getNameByConstant($subject->getRole()));
        self::assertSame($id, $subject->getId());
        self::assertSame($class, $subject->getClass());
        self::assertSame($lang, $subject->getLang());
        self::assertSame($label, $subject->getLabel());
        self::assertEquals(new FlowCollection(), $subject->getComponents());
    }

    public function testCreateWithDefaultValues(): void
    {
        $subject = new FigCaption();

        self::assertSame('', $subject->getTitle());
        self::assertNull($subject->getRole());
        self::assertSame('', $subject->getId());
        self::assertSame('', $subject->getClass());
        self::assertSame('', $subject->getLang());
        self::assertSame('', $subject->getLabel());
        self::assertEquals(new FlowCollection(), $subject->getComponents());
    }

    public function testSetContent(): void
    {
        $subject = new FigCaption();
        $content = new FlowCollection(
            [
                new P(),
                new Br(),
                new A('blah'),
            ]
        );

        $subject->setContent($content);

        self::assertEquals($content, $subject->getContent());
        self::assertEquals($content, $subject->getComponents());
    }

    public function testGetQtiClassName(): void
    {
        $subject = new FigCaption();

        self::assertEquals('figcaption', $subject->getQtiClassName());
    }
}
