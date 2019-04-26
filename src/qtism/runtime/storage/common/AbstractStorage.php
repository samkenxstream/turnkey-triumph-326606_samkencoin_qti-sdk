<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013-2016 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 *
 */

namespace qtism\runtime\storage\common;

use qtism\runtime\tests\AbstractSessionManager;
use qtism\runtime\tests\AssessmentTestSession;
use qtism\data\AssessmentTest;

/**
 * The AbstractStorage class is extended by any class that claims to offer an AssessmentTestSession Storage Service. 
 * 
 * It will provide all the functionalities to make AssessmentTestSession objects 
 * persistant and retrievable at will. An instance of AbstractStorage is dedicated
 * to store AssessmentTestSession of a unique AssessmentTest definition. To deal with
 * AssessmentTestSession objects bound to various AssessmentTest definitions, different
 * instances of AbstractStorage must be used.
 *
 * An AssessmentTestSession Storage Service must be able to:
 *
 * * Instantiate a new AssessmentTestSession object, and assign it a unique identifier.
 * * Persist an AssessmentTestSession object for a later retrieval.
 * * Retrieve an AssessmentTestSession from its session ID.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
abstract class AbstractStorage
{
    /**
     * The manager to be used to instantiate AssessmentTestSession and AssessmentItemSession objects.
     *
     * @var \qtism\runtime\tests\AbstractSessionManager
     */
    private $manager;
    
    private $assessmentTest;

    /**
     * Create a new AbstracStorage object.
     *
     * @param \qtism\runtime\tests\AbstractSessionManager $manager The manager to be used to instantiate AssessmentTestSession and AssessmentItemSession objects.
     * @param \qtism\data\AssessmentTest $test The AssessmentTest definition object the AbstractStorage is specialized in.
     */
    public function __construct(AbstractSessionManager $manager, AssessmentTest $test)
    {
        $this->setManager($manager);
        $this->setAssessmentTest($test);
    }

    /**
     * Set the manager to be used to instantiate AssessmentTestSession and AssessmentItemSession objects.
     *
     * @param \qtism\runtime\tests\AbstractSessionManager $manager
     */
    protected function setManager(AbstractSessionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get the manager to be used to instantiate AssessmentTestSession and AssessmentItemSession objects.
     *
     * @return \qtism\runtime\tests\AbstractSessionManager
     */
    protected function getManager()
    {
        return $this->manager;
    }
    
    /**
     * Set the AssessmentTest object.
     * 
     * Set the AssessmentTest object the AssessmentTestSession Storage Service is specialized in.
     * 
     * @param \qtism\data\AssessmentTest $test
     */
    protected function setAssessmentTest(AssessmentTest $test) {
        $this->assessmentTest = $test;
    }
    
    /**
     * Get the AssessmentTest object.
     * 
     * Get the AssessmentTest object the AssessmentTestSession Storage Service is specialized in.
     *
     */
    protected function getAssessmentTest()
    {
        return $this->assessmentTest;
    }

    /**
     * Instantiate an AssessmentTestSession from the AssessmentTest the AssessmentTestSession Storage Service implementation is specialized in.
     * 
     * An AssessmentTestSession object is returned, with a session ID that will
     * make client code able to retrive persisted AssessmentTestSession objects later on.
     *
     * If $sessionId is not provided, the AssessmentTestSession Storage Service implementation
     * must generate a unique session ID on its own. The ID generation algorithm is free, depending
     * on implementation needs.
     * 
     * Instantiating the AssessmentTestSession does not mean it is persisted. If you want
     * to persist its state, call the persist() method.
     *
     * @param integer $config (optional) The configuration to be taken into account for the instantiated AssessmentTestSession object.
     * @param string $sessionId (optional) A $sessionId to be used to identify the instantiated AssessmentTest. If not given (empty string), an ID will be generated by the storage implementation.
     * @throws \qtism\runtime\storage\common\StorageException If an error occurs while instantiating the AssessmentTestSession object.
     */
    abstract public function instantiate($config = 0, $sessionId = '');

    /**
     * Persist an AssessmentTestSession object for a later retrieval.
     *
     * @param \qtism\runtime\tests\AssessmentTestSession $assessmentTestSession An AssessmentTestSession object to be persisted.
     * @throws \qtism\runtime\storage\common\StorageException If an error occurs while persisting the $assessmentTestSession object.
     */
    abstract public function persist(AssessmentTestSession $assessmentTestSession);

    /**
     * Retrieve a previously persisted AssessmentTestSession object by session ID.
     *
     * @param string $sessionId The Session ID of the AssessmentTestSession object to be retrieved.
     * @throws \qtism\runtime\storage\common\StorageException If an error occurs while retrieving the AssessmentTestSession object.
     */
    abstract public function retrieve($sessionId);
    
    /**
     * Whether or not an AssessmentTestSession object exits in persistence for a given session ID.
     * 
     * This method allows you to know whether or not an AssessmentTestSession object
     * with session ID $sessionId exists as a persistent entity.
     * 
     * @param string $sessionId
     * @return boolean
     * @throws \qtism\runtime\storage\common\StorageException If an error occurs while determining whether the AssessmentTestSession object exists in the storage.
     */
    abstract public function exists($sessionId);
    
    /**
     * Delete an AssessmentTestSession object from persistence.
     * 
     * This method enables you to delete a persistent AssessmentTestSession object from the persistent storage.
     * 
     * If an AssessmentTestSession object is effectively found by its session ID in the
     * persistent storage, and is deleted successfully, this method returns true.
     * 
     * However, if no AssessmentTestSession object could be found from its session ID while deleting under
     * normal circumstances, this method returns false.
     * 
     * Finally, if an unexpected error occurs while deleting the AssessmentTestSession object (e.g. network issue, ...), 
     * a StorageException is thrown.
     * 
     * @param \qtism\runtime\tests\AssessmentTestSession The AssessmentTestSession object to be deleted.
     * @return boolean
     * @throws \qtism\runtime\storage\common\StorageException If an error occurs while deleting the AssessmentTestSession object.
     */
    abstract public function delete(AssessmentTestSession $assessmentTestSession);
}
