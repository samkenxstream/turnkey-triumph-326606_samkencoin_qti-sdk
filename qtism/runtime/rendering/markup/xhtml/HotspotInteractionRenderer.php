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
 * Copyright (c) 2013-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\runtime\rendering\markup\xhtml;

use DOMDocumentFragment;
use qtism\data\QtiComponent;

/**
 * HotspotInteraction renderer. Rendered components will be transformed as
 * 'div' elements with a 'qti-hotspotInteraction' additional CSS class.
 *
 * The following data-X attributes will be rendered:
 *
 * * data-responseIdentifier = qti:interaction->responseIdentifier
 * * data-max-choices = qti:hotspotInteraction->maxChoices
 * * data-min-choices = qti:hotspotInteraction->minChoices
 */
class HotspotInteractionRenderer extends GraphicInteractionRenderer
{
    /**
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = '')
    {
        parent::appendAttributes($fragment, $component, $base);
        $this->additionalClass('qti-hotspotInteraction');

        $fragment->firstChild->setAttribute('data-max-choices', $component->getMaxChoices());
        $fragment->firstChild->setAttribute('data-min-choices', $component->getMinChoices());
    }
}
