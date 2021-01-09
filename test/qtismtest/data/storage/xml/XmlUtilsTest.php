<?php

namespace qtismtest\data\storage\xml;

use DOMDocument;
use DOMElement;
use qtism\data\storage\xml\Utils;
use qtismtest\QtiSmTestCase;

/**
 * Class XmlUtilsTest
 */
class XmlUtilsTest extends QtiSmTestCase
{
    /**
     * @param string $originalXmlString
     * @param string $expectedXmlString
     * @dataProvider anonimizeElementProvider
     */
    public function testAnonimizeElement($originalXmlString, $expectedXmlString)
    {
        $elt = $this->createDOMElement($originalXmlString);
        $newElt = Utils::anonimizeElement($elt);

        $this::assertEquals($expectedXmlString, $newElt->ownerDocument->saveXML($newElt));
    }

    /**
     * @return array
     */
    public function anonimizeElementProvider()
    {
        return [
            [
                '<m:math xmlns:m="http://www.w3.org/1998/Math/MathML" display="inline"><m:mn>1</m:mn><m:mo>+</m:mo><m:mn>2</m:mn><m:mo>=</m:mo><m:mn>3</m:mn></m:math>',
                '<math display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
            ],

            [
                '<math xmlns="http://www.w3.org/1998/Math/MathML" display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
                '<math display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
            ],

            [
                '<math xmlns="http://www.w3.org/1998/Math/MathML" display="inline"><mn><![CDATA[1]]></mn><mo>+</mo><mn><![CDATA[2]]></mn><mo>=</mo><mn><![CDATA[3]]></mn></math>',
                '<math display="inline"><mn><![CDATA[1]]></mn><mo>+</mo><mn><![CDATA[2]]></mn><mo>=</mo><mn><![CDATA[3]]></mn></math>',
            ],
        ];
    }

    /**
     * @dataProvider getXsdLocationProvider
     *
     * @param string $file
     * @param string $namespaceUri
     * @param bool|string $expectedLocation
     */
    public function testGetXsdLocation($file, $namespaceUri, $expectedLocation)
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->load($file);
        $location = Utils::getXsdLocation($document, $namespaceUri);

        $this::assertSame($expectedLocation, $location);
    }

    /**
     * @return array
     */
    public function getXsdLocationProvider()
    {
        return [
            // Valid.
            [
                self::samplesDir() . 'custom/items/versiondetection_20_singlespace.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_multispace.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_singletab.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_multitab.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_leading.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_trailing.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'ims/items/2_1_1/associate.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p1',
                'http://www.imsglobal.org/xsd/qti/qtiv2p1/imsqti_v2p1p1.xsd',
            ],
            [
                self::samplesDir() . 'ims/items/2_1/associate.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p1',
                'http://www.imsglobal.org/xsd/qti/qtiv2p1/imsqti_v2p1.xsd',
            ],

            // Invalid
            [
                self::samplesDir() . 'custom/items/versiondetection_20_trailing.xml',
                'wrong-ns',
                false,
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_emptyschemalocation.xml',
                'wrong-ns',
                false,
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_noschemalocationattribute.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                false,
            ],
        ];
    }

    public function testChangeNamespaceElementName()
    {
        $foo = $this->createDOMElement('<foo xmlns:bar="http://baz" bar:attr="foo"/>');
        $foo = Utils::changeElementName($foo, 'bar');
        $this::assertEquals('bar', $foo->tagName);
        $this::assertEquals('foo', $foo->getAttributeNS('http://baz', 'attr'));
    }

    /**
     * @dataProvider escapeXmlSpecialCharsProvider
     * @param string $str
     * @param bool $isAttribute
     * @param string $expected
     */
    public function testEscapeXmlSpecialChars($str, $isAttribute, $expected)
    {
        $this::assertEquals($expected, Utils::escapeXmlSpecialChars($str, $isAttribute));
    }

    /**
     * @return array
     */
    public function escapeXmlSpecialCharsProvider()
    {
        return [
            ['\'"&<>', false, '&apos;&quot;&amp;&lt;&gt;'],
            ['<blah>', false, '&lt;blah&gt;'],
            ['blah', false, 'blah'],
            ['&"', true, '&amp;&quot;'],
            ['blah & "cool" & \'cool\'', true, 'blah &amp; &quot;cool&quot; &amp; \'cool\''],
        ];
    }

    /**
     * @dataProvider webComponentFriendlyAttributeNameProvider
     * @param string $qtiName
     * @param string $expected
     */
    public function testWebComponentFriendlyAttributeName($qtiName, $expected)
    {
        $this::assertEquals($expected, Utils::webComponentFriendlyAttributeName($qtiName));
    }

    /**
     * @return array
     */
    public function webComponentFriendlyAttributeNameProvider()
    {
        return [
            ['minChoices', 'min-choices'],
            ['identifier', 'identifier'],
        ];
    }

    /**
     * @dataProvider webComponentFriendlyClassNameProvider
     * @param string $qtiName
     * @param string $expected
     */
    public function testWebComponentFriendlyClassName($qtiName, $expected)
    {
        $this::assertEquals($expected, Utils::webComponentFriendlyClassName($qtiName));
    }

    /**
     * @return array
     */
    public function webComponentFriendlyClassNameProvider()
    {
        return [
            ['choiceInteraction', 'qti-choice-interaction'],
            ['simpleChoice', 'qti-simple-choice'],
            ['prompt', 'qti-prompt'],
        ];
    }

    /**
     * @dataProvider qtiFriendlyNameProvider
     * @param string $wcName
     * @param string $expected
     */
    public function testQtiFriendlyName($wcName, $expected)
    {
        $this::assertEquals($expected, Utils::qtiFriendlyName($wcName));
    }

    /**
     * @return array
     */
    public function qtiFriendlyNameProvider()
    {
        return [
            ['qti-choice-interaction', 'choiceInteraction'],
            ['qti-prompt', 'prompt'],
            ['min-choices', 'minChoices'],
        ];
    }

    /**
     * @dataProvider getDOMElementAttributeAsProvider
     * @param DOMElement $element
     * @param string $attribute
     * @param string $datatype
     * @param mixed $expected
     */
    public function testGetDOMElementAttributeAs(DOMElement $element, $attribute, $datatype, $expected)
    {
        $result = Utils::getDOMElementAttributeAs($element, $attribute, $datatype);
        $this::assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function getDOMElementAttributeAsProvider()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML('<foo string="str" integer="1" float="1.1" double="1.1" boolean="true"/>');
        $elt = $dom->documentElement;

        return [
            [$elt, 'string', 'string', 'str'],
            [$elt, 'integer', 'integer', 1],
            [$elt, 'float', 'float', 1.1],
            [$elt, 'double', 'double', 1.1],
            [$elt, 'boolean', 'boolean', true],
        ];
    }

    public function testGetChildElementsByTagName()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        // There are 3 child elements. 2 at the first level, 1 at the second.
        // We should find only 2 direct child elements.
        $dom->loadXML('<parent><child/><child/><parent><child/></parent></parent>');
        $element = $dom->documentElement;

        $this::assertEquals(2, count(Utils::getChildElementsByTagName($element, 'child')));
    }

    public function testGetChildElementsByTagNameMultiple()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML('<parent><child/><child/><grandChild/><uncle/></parent>');
        $element = $dom->documentElement;

        $this::assertEquals(3, count(Utils::getChildElementsByTagName($element, ['child', 'grandChild'])));
    }

    public function testGetChildElementsByTagNameEmpty()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        // There is only 1 child but at the second level. Nothing
        // should be found.
        $dom->loadXML('<parent><parent><child/></parent></parent>');
        $element = $dom->documentElement;

        $this::assertEquals(0, count(Utils::getChildElementsByTagName($element, 'child')));
    }
}
