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
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Julien Sébire <julien@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content\xhtml\html5;

use InvalidArgumentException;
use qtism\data\content\FlowCollection;
use qtism\data\content\FlowStatic;
use qtism\data\content\FlowTrait;

/**
 * The XHTML figure class.
 */
class Figure extends Html5LayoutElement
{
    /**
     * The optional figure caption. When set, FigCaption is unique.
     *
     * @var FigCaption|null An optional unique FigCaption object.
     * @qtism-bean-property
     */
    private $figCaption;

    public function setFigCaption(?FigCaption $figCaption): void
    {
        $this->figCaption = $figCaption;
    }

    public function getFigCaption(): ?FigCaption
    {
        return $this->figCaption;
    }

    public function getComponents(): FlowCollection
    {
        $flowObjects = $this->content->getArrayCopy();
        if ($this->figCaption !== null) {
            $flowObjects[] = $this->figCaption;
        }

        return new FlowCollection($flowObjects);
    }

    public function setContent(FlowCollection $content): void
    {
        // Separates caption from the rest of components to insure uniqueness.
        $internalContent = new FlowCollection();
        foreach ($content as $flowComponent) {
            if ($flowComponent instanceof FigCaption) {
                $this->setFigCaption($flowComponent);
                continue;
            }

            $internalContent->attach($flowComponent);
        }

        $this->content = $internalContent;
    }

    public function getQtiClassName(): string
    {
        return 'figure';
    }
}
