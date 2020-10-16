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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Julien Sébire <julien@taotesting.com>
 * @license GPLv2
 */

namespace qtism\common\datatypes\files;

use qtism\common\datatypes\QtiFile;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use RuntimeException;

/**
 * An implementation of File storing only a hash of the file.
 * File contents has to be previously persisted externally.
 * Hash string will serve the purpose of comparing files only.
 */
class FileHash implements QtiFile
{
    /**
     * Key to use in json payload to trigger the storage of a file hash instead
     * of a hash.
     */
    const FILE_HASH_KEY = 'fileHash';

    /**
     * The path to the file on the persistent storage.
     *
     * @var string
     */
    private $path;

    /**
     * The MIME type of the file content.
     *
     * @var string
     */
    private $mimeType;

    /**
     * The original file name.
     *
     * @var string
     */
    private $filename;

    /**
     * The hash of the file contents.
     *
     * @var string
     */
    private $hash;

    /**
     * Create a new FileHash object.
     *
     * @param string $path The path where the file is actually stored.
     * @param string $mimeType The mime-type of the file.
     * @param string $filename The path to the file on the external persistent storage.
     * @param string $hash The hash of the file.
     * @throws RuntimeException If the file cannot be retrieved correctly.
     */
    public function __construct($path, $mimeType, $filename, $hash)
    {
        $this->setPath($path);
        $this->setMimeType($mimeType);
        $this->setFilename($filename);
        $this->setHash($hash);
    }

    /**
     * Set the path to the file.
     *
     * @param string $path
     */
    protected function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the path to the file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the mime-type of the file.
     *
     * @param string $mimeType
     */
    protected function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Get the mime-type of the file.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Get the name of the file.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the name of the file.
     *
     * @param string $filename
     */
    protected function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get the sequence of bytes composing the hash of the file.
     */
    public function getData()
    {
        return $this->getHash();
    }

    /**
     * Returns nothing because the content of the file is stored externally
     * and thus not accessible in here.
     */
    public function getStream()
    {
    }

    /**
     * Get the hash of the file.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the hash of the file.
     *
     * @param string $hash
     */
    protected function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Get the cardinality of the File value.
     *
     * @return int A value from the Cardinality enumeration.
     */
    public function getCardinality()
    {
        return Cardinality::SINGLE;
    }

    /**
     * Get the baseType of the File value.
     *
     * @return int A value from the BaseType enumeration.
     */
    public function getBaseType()
    {
        return BaseType::FILE;
    }

    /**
     * Whether or not the File has a file name.
     *
     * @return bool
     */
    public function hasFilename()
    {
        return true;
    }

    /**
     * Whether or not two File objects are equals. Two File values
     * are considered to be identical if they have the same file name,
     * mime-type and hash.
     *
     * @param mixed $obj
     * @return bool
     */
    public function equals($obj)
    {
        if (!$obj instanceof self) {
            return false;
        }

        return $this->getPath() === $obj->getPath()
            && $this->getMimeType() === $obj->getMimeType()
            && $this->getFilename() === $obj->getFilename()
            && $this->getHash() === $obj->getHash();
    }

    /**
     * Get the unique identifier of the File.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getPath();
    }

    /**
     * File as a string
     *
     * Returns the file name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFilename();
    }
}
