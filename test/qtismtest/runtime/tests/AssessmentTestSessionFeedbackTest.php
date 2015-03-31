<?php
namespace qtismtest\runtime\tests;

use qtismtest\QtiSmAssessmentTestSessionTestCase;
use qtism\common\datatypes\Identifier;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\runtime\common\ResponseVariable;
use qtism\runtime\common\State;
use qtism\runtime\tests\AssessmentTestSessionState;
use qtism\runtime\tests\AssessmentTestSession;

class AssessmentTestSessionFeedbackTest extends QtiSmAssessmentTestSessionTestCase {
    
    public function testLinearAssessmentTestDuring() {
        $url = self::samplesDir() . 'custom/runtime/testfeedbacks/linear_assessmenttest_during.xml';
        $testSession = self::instantiate($url);
        
        $testSession->beginTestSession();
        
        // Attempt on Q01. 
        $testSession->beginAttempt();
        $testSession->endAttempt(new State(array(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier('ChoiceA')))));
        $current = $testSession->getRoute()->current();
        
        // The call to moveNext must put the state of the session into MODAL_FEEDBACK. Be carefull,
        // the current item should still be Q01.
        $testSession->moveNext();
        $this->assertEquals(AssessmentTestSessionState::MODAL_FEEDBACK, $testSession->getState());
        $this->assertEquals('Q01', $testSession->getCurrentAssessmentItemRef()->getIdentifier());
        
        // An additional call to moveNext will make the item flow go to Q02.
        $testSession->moveNext();
        $this->assertEquals(AssessmentTestSessionState::INTERACTING, $testSession->getState());
        $this->assertEquals('Q02', $testSession->getCurrentAssessmentItemRef()->getIdentifier());
        
        // Attempt on Q02.
        $testSession->beginAttempt();
        $testSession->endAttempt(new State(array(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier('ChoiceB')))));
        
        // The call to moveNext must again put the state of the session into MODAL_FEEDBACK without
        // moving to the next item.
        $testSession->moveNext();
        $this->assertEquals(AssessmentTestSessionState::MODAL_FEEDBACK, $testSession->getState());
        $this->assertEquals('Q02', $testSession->getCurrentAssessmentItemRef()->getIdentifier());
        
        // A new moveNext will end the test.
        $testSession->moveNext();
        $this->assertEquals(AssessmentTestSessionState::CLOSED, $testSession->getState());
    }
    
    public function testLinearAssessmentTestAtEndShow() {
        $url = self::samplesDir() . 'custom/runtime/testfeedbacks/linear_assessmenttest_atend.xml';
        $testSession = self::instantiate($url);
        
        $testSession->beginTestSession();
        $this->assertEquals('false', $testSession['FULLCORRECT']->getValue());
        
        // Attempt on Q01. Correct response.
        $testSession->beginAttempt();
        $testSession->endAttempt(new State(array(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier('ChoiceA')))));
        $testSession->moveNext();
        
        // No feedback must be shown because we are not at the end of test.
        // Moreover, the FULLCORRECT's value should still be 'false'.
        $this->assertEquals('false', $testSession['FULLCORRECT']->getValue());
        $this->assertEquals(AssessmentTestSessionState::INTERACTING, $testSession->getState());
        
        // Attempt on Q02. Correct response.
        $testSession->beginAttempt();
        $testSession->endAttempt(new State(array(new ResponseVariable('RESPONSE', Cardinality::SINGLE, BaseType::IDENTIFIER, new Identifier('ChoiceB')))));
        $testSession->moveNext();
        
        // Because it is the end of the test, and the two responses are correct, the feedback must be shown.
        $this->assertEquals(AssessmentTestSessionState::MODAL_FEEDBACK, $testSession->getState());
        
        // Now we can perform a new moveNext to finish the test.
        $testSession->moveNext();
        $this->assertEquals(AssessmentTestSessionState::CLOSED, $testSession->getState()); 
    }
}
