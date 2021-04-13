<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * Copyright (c) 2013-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\storage\xml;

use Exception;
use qtism\data\storage\StorageException;
use qtism\data\storage\xml\marshalling\MarshallerNotFoundException;

/**
 * An exception type that represent an error when dealing with QTI-XML files.
 */
class XmlStorageException extends StorageException
{
    /**
     * An error occurred while resolving an external resource.
     *
     * @var int
     */
    const RESOLUTION = 10;

    /**
     * The error is related to XSD validation.
     *
     * @var int
     */
    const XSD_VALIDATION = 11;

    /**
     * An array containing libxml errors.
     *
     * @var LibXmlErrorCollection
     */
    private $errors = null;

    /**
     * Create a new XmlStorageException object.
     *
     * @param string $message A human-readable message describing the exception.
     * @param int $code An error code.
     * @param Exception $previous An optional previous exception which is the cause of this one.
     * @param LibXmlErrorCollection $errors An array of errors (stdClass) as generated by libxml_get_errors().
     */
    public function __construct($message, $code = 0, $previous = null, LibXmlErrorCollection $errors = null)
    {
        parent::__construct($message, $code, $previous);

        if (empty($errors)) {
            $errors = new LibXmlErrorCollection();
        }

        $this->setErrors($errors);
    }

    /**
     * @param string $msg
     * @param array $libXmlErrors
     * @return static
     */
    public static function createValidationException(string $msg, array $libXmlErrors): self
    {
        return new self(
            $msg,
            self::XSD_VALIDATION,
            null,
            new LibXmlErrorCollection($libXmlErrors)
        );
    }

    /**
     * @param MarshallerNotFoundException $e
     * @param string $version
     * @return mixed
     */
    public static function unsupportedComponentInVersion(
        MarshallerNotFoundException $e,
        string $version
    ): self {
        $msg = "'" . $e->getQtiClassName() . "' components are not supported in QTI version '${version}'.";
        return new self($msg, self::VERSION, $e);
    }
    
    /**
     * Set the errors returned by libxml_get_errors.
     *
     * @param LibXmlErrorCollection $errors A collection of LibXMLError objects.
     */
    protected function setErrors(LibXmlErrorCollection $errors = null)
    {
        $this->errors = $errors;
    }

    /**
     * Get the errors generated by libxml_get_errors.
     *
     * @return LibXmlErrorCollection A collection of LibXMLError objects.
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
