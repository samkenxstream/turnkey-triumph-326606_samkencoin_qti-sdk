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

namespace qtism\data\storage\xml\marshalling;

use DOMElement;
use qtism\common\utils\Version;
use qtism\data\content\BodyElement;
use qtism\data\content\enums\Role;

/**
 * Marshalling/Unmarshalling implementation for generic Html5.
 */
abstract class Html5ElementMarshaller extends Marshaller
{
    /**
     * Fill $element with the attributes of $bodyElement.
     *
     * @param DOMElement $element The element from where the attribute values will be
     * @param BodyElement $bodyElement The bodyElement to be fill.
     */
    protected function fillElement(DOMElement $element, BodyElement $bodyElement)
    {
        if ($bodyElement->hasTitle()) {
            $this->setDOMElementAttribute($element, 'title', $bodyElement->getTitle());
        }

        if ($bodyElement->hasRole()) {
            $this->setDOMElementAttribute($element, 'role', Role::getNameByConstant($bodyElement->getRole()));
        }

        parent::fillElement($element, $bodyElement);
    }

    /**
     * Fill $bodyElement with the following Html 5 element attributes:
     *
     * * title
     * * role
     *
     * @param BodyElement $bodyElement The bodyElement to fill.
     * @param DOMElement $element The DOMElement object from where the attribute values must be retrieved.
     * @throws UnmarshallingException If one of the attributes of $element is not valid.
     */
    protected function fillBodyElement(BodyElement $bodyElement, DOMElement $element)
    {
        if (Version::compare($this->getVersion(), '2.2.0', '>=') === true) {
            $title = $this->getDOMElementAttributeAs($element, 'title');
            $bodyElement->setTitle($title);

            $role = $this->getDOMElementAttributeAs($element, 'role', Role::class);
            $bodyElement->setRole($role);
        }

        parent::fillBodyElement($bodyElement, $element);
    }
}
