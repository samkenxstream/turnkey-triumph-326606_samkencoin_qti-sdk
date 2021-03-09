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
 * Copyright (c) 2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Julien Sébire <julien@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content\xhtml\html5;

use qtism\data\content\BlockStatic;

/**
 * Html 5 Audio element.
 * An audio element represents a sound or audio stream. User agents should not
 * show this content to the user; it is intended for older Web browsers which
 * do not support audio, so that legacy audio plugins can be tried, or to show
 * text to the users of these older browsers informing them of how to access
 * the audio contents.
 */
class Audio extends Html5Media implements BlockStatic
{
    public function getQtiClassName(): string
    {
        return 'audio';
    }
}
