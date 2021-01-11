<?php

namespace qtismtest\data\storage\xml;

use qtism\data\storage\xml\XmlDocument;
use qtism\data\storage\xml\XmlStorageException;
use qtismtest\QtiSmTestCase;
use qtism\data\AssessmentItemRef;
use qtism\data\AssessmentSectionRef;
use qtism\data\TestPart;
use qtism\data\AssessmentTest;
use qtism\data\storage\xml\LibXmlErrorCollection;

/**
 * Class XmlAssessmentTestDocumentTest
 */
class XmlAssessmentTestDocumentTest extends QtiSmTestCase
{
    public function testLoad()
    {
        $uri = __DIR__ . '/../../../../samples/ims/tests/interaction_mix_sachsen/interaction_mix_sachsen.xml';
        $doc = new XmlDocument('2.1');
        $doc->load($uri);

        $this::assertInstanceOf(XmlDocument::class, $doc);
        $this::assertInstanceOf(AssessmentTest::class, $doc->getDocumentComponent());
    }

    public function testLoadFileDoesNotExist()
    {
        // This file does not exist.
        $uri = __DIR__ . '/../../../../samples/invalid/abcd.xml';
        $doc = new XmlDocument('2.1');
        $this->expectException(XmlStorageException::class);
        $doc->load($uri);
    }

    public function testLoadFileMalformed()
    {
        // This file contains malformed xml markup.
        $uri = __DIR__ . '/../../../../samples/invalid/malformed.xml';
        $doc = new XmlDocument('2.1');

        try {
            $doc->load($uri);
            $this::assertFalse(true); // An exception must have been thrown.
        } catch (XmlStorageException $e) {
            $this::assertIsString($e->getMessage());
            $this::assertInstanceOf(LibXmlErrorCollection::class, $e->getErrors());
            $this::assertGreaterThan(0, count($e->getErrors()));
        }
    }

    public function testLoadSimpleItemSessionControlOnTestPart()
    {
        $doc = new XmlDocument('2.1');
        $doc->load(self::samplesDir() . 'custom/simple_itemsessioncontrol_testpart.xml');
        $testParts = $doc->getDocumentComponent()->getTestParts();
        $this::assertTrue($testParts['testPartId']->hasItemSessionControl());
        $this::assertIsInt($testParts['testPartId']->getItemSessionControl()->getMaxAttempts());
        $this::assertEquals(0, $testParts['testPartId']->getItemSessionControl()->getMaxAttempts());
    }

    public function testSaveSimpleItemSessionControlOnTestPart()
    {
        $doc = new XmlDocument('2.1');
        $doc->load(self::samplesDir() . 'custom/simple_itemsessioncontrol_testpart.xml');
        $file = tempnam('/tmp', 'qsm');
        $doc->save($file);

        $doc = new XmlDocument('2.1');
        $doc->load($file);
        $testParts = $doc->getDocumentComponent()->getTestParts();
        $this::assertTrue($testParts['testPartId']->hasItemSessionControl());
        $this::assertIsInt($testParts['testPartId']->getItemSessionControl()->getMaxAttempts());
        $this::assertEquals(0, $testParts['testPartId']->getItemSessionControl()->getMaxAttempts());

        unlink($file);
    }

    public function testFullyQualified()
    {
        $uri = __DIR__ . '/../../../../samples/custom/fully_qualified_assessmenttest.xml';
        $doc = new XmlDocument('2.1');
        $doc->load($uri);
        $doc->schemaValidate();

        $this::assertInstanceOf(XmlDocument::class, $doc);
        $this::assertInstanceOf(AssessmentTest::class, $doc->getDocumentComponent());
    }

    public function testItemSessionControls()
    {
        $doc = new XmlDocument('2.1');
        $doc->load(self::samplesDir() . 'custom/runtime/routeitem_itemsessioncontrols.xml');

        // Q01.
        $q01 = $doc->getDocumentComponent()->getComponentByIdentifier('Q01');
        $this::assertInstanceOf(AssessmentItemRef::class, $q01);
        $this::assertEquals(2, $q01->getItemSessionControl()->getMaxAttempts());

        // P02.
        $p02 = $doc->getDocumentComponent()->getComponentByIdentifier('P02');
        $this::assertInstanceOf(TestPart::class, $p02);
        $this::assertEquals(4, $p02->getItemSessionControl()->getMaxAttempts());
    }

    public function testAssessmentSectionRefsInTestParts()
    {
        $doc = new XmlDocument();
        $doc->load(self::samplesDir() . 'custom/tests/nested_assessment_section_refs/test_definition/test.xml', true);

        $testParts = $doc->getDocumentComponent()->getTestParts();
        $this::assertTrue(isset($testParts['T01']));

        $sectionParts = $testParts['T01']->getAssessmentSections();
        $this::assertTrue(isset($sectionParts['SR01']));
        $this::assertInstanceOf(AssessmentSectionRef::class, $sectionParts['SR01']);
    }

    public function testIncludeAssessmentSectionRefsInTestParts()
    {
        $doc = new XmlDocument();
        $doc->load(self::samplesDir() . 'custom/tests/nested_assessment_section_refs/test_definition/test.xml', true);
        $doc->includeAssessmentSectionRefs();

        $root = $doc->getDocumentComponent();

        $testParts = $root->getTestParts();
        $this::assertTrue(isset($testParts['T01']));

        // Check that assessmentSectionRef 'SR01' has been resolved.
        $sectionParts = $testParts['T01']->getAssessmentSections();

        $this::assertTrue(isset($sectionParts['S01']));
        $this::assertFalse(isset($sectionParts['SR01']));
        $this::assertTrue(isset($sectionParts['S01']->getSectionParts()['S02']));

        // Check that the final assessmentSection contains the assessmentItemRefs.
        $assessmentItemRefs = $sectionParts['S01']->getSectionParts()['S02']->getSectionParts();
        $this::assertCount(3, $assessmentItemRefs);

        $this::assertInstanceOf(AssessmentItemRef::class, $assessmentItemRefs['Q01']);
        $this::assertEquals('../sections/../sections/../items/question1.xml', $assessmentItemRefs['Q01']->getHref());
        $this::assertInstanceOf(AssessmentItemRef::class, $assessmentItemRefs['Q02']);
        $this::assertEquals('../sections/../sections/../items/question2.xml', $assessmentItemRefs['Q02']->getHref());
        $this::assertInstanceOf(AssessmentItemRef::class, $assessmentItemRefs['Q03']);
        $this::assertEquals('../sections/../sections/../items/question3.xml', $assessmentItemRefs['Q03']->getHref());
    }

    /**
     * @param $uri
     * @return string
     */
    private static function decorateUri($uri)
    {
        return __DIR__ . '/../../../../samples/ims/tests/' . $uri;
    }
}
