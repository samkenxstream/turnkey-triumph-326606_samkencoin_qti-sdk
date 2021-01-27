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
use InvalidArgumentException;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;
use qtism\data\ShowHide;

/**
 * The Marshaller implementation for InlineChoice elements of the content model.
 */
class InlineChoiceMarshaller extends ContentMarshaller
{
    /**
     * @param DOMElement $element
     * @param QtiComponentCollection $children
     * @return mixed
     * @throws UnmarshallingException
     */
    protected function unmarshallChildrenKnown(DOMElement $element, QtiComponentCollection $children)
    {
        if (($identifier = $this->getDOMElementAttributeAs($element, 'identifier')) !== null) {
            $fqClass = $this->lookupClass($element);

            try {
                $component = new $fqClass($identifier);
            } catch (InvalidArgumentException $e) {
                $msg = "'${identifier}' is not a valid identifier for an 'inlineChoice' element.";
                throw new UnmarshallingException($msg, $element, $e);
            }

            try {
                $component->setContent(new TextOrVariableCollection($children->getArrayCopy()));
            } catch (InvalidArgumentException $e) {
                $msg = "'inlineChoice' elements must only contain text or 'printedVariable' elements.";
                throw new UnmarshallingException($msg, $element, $e);
            }

            if (($fixed = $this->getDOMElementAttributeAs($element, 'fixed', 'boolean')) !== null) {
                $component->setFixed($fixed);
            }

            if (($templateIdentifier = $this->getDOMElementAttributeAs($element, 'templateIdentifier')) !== null) {
                $component->setTemplateIdentifier($templateIdentifier);
            }

            if (($showHide = $this->getDOMElementAttributeAs($element, 'showHide')) !== null) {
                $component->setShowHide(ShowHide::getConstantByName($showHide));
            }

            $this->fillBodyElement($component, $element);

            return $component;
        } else {
            $msg = "The mandatory 'identifier' attribute is missing from the 'simpleChoice' element.";
            throw new UnmarshallingException($msg, $element);
        }
    }

    /**
     * @param QtiComponent $component
     * @param array $elements
     * @return DOMElement
     */
    protected function marshallChildrenKnown(QtiComponent $component, array $elements)
    {
        $element = $this->createElement($component);
        $this->fillElement($element, $component);
        $this->setDOMElementAttribute($element, 'identifier', $component->getIdentifier());

        if ($component->isFixed() === true) {
            $this->setDOMElementAttribute($element, 'fixed', true);
        }

        if ($component->hasTemplateIdentifier() === true) {
            $this->setDOMElementAttribute($element, 'templateIdentifier', $component->getTemplateIdentifier());
        }

        if ($component->getShowHide() !== ShowHide::SHOW) {
            $this->setDOMElementAttribute($element, 'showHide', ShowHide::getNameByConstant(ShowHide::HIDE));
        }

        foreach ($elements as $e) {
            $element->appendChild($e);
        }

        return $element;
    }

    protected function setLookupClasses()
    {
        $this->lookupClasses = ["qtism\\data\\content\\interactions"];
    }
}
