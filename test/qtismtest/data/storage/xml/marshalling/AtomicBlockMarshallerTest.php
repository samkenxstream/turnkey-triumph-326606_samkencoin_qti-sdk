<?php

namespace qtismtest\data\storage\xml\marshalling;

use DOMDocument;
use qtism\data\content\InlineCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Em;
use qtism\data\content\xhtml\text\P;
use qtismtest\QtiSmTestCase;

/**
 * Class AtomicBlockMarshallerTest
 */
class AtomicBlockMarshallerTest extends QtiSmTestCase
{
    public function testMarshallP()
    {
        $p = new P('my-p');
        $em = new Em();
        $em->setContent(new InlineCollection([new TextRun('simple')]));
        $p->setContent(new InlineCollection([new TextRun('This text is a '), $em, new TextRun(' test.')]));

        $marshaller = $this->getMarshallerFactory('2.1.0')->createMarshaller($p);
        $element = $marshaller->marshall($p);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $element = $dom->importNode($element, true);
        $this::assertEquals('<p id="my-p">This text is a <em>simple</em> test.</p>', $dom->saveXML($element));
    }

    public function testUnmarshallP()
    {
        $p = $this->createComponentFromXml('
	        <p id="my-p">
                This text is
                a <em>simple</em> test.
            </p>
	    ');

        $this::assertInstanceOf(P::class, $p);
        $this::assertEquals('my-p', $p->getId());
        $this::assertEquals(3, count($p->getContent()));

        $content = $p->getContent();
        $this::assertEquals("\n                This text is\n                a ", $content[0]->getContent());
        $em = $content[1];
        $this::assertInstanceOf(Em::class, $em);
        $emContent = $em->getContent();
        $this::assertEquals('simple', $emContent[0]->getContent());
        $this::assertEquals(" test.\n            ", $content[2]->getContent());
    }
}
