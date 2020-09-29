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

namespace qtism\runtime\expressions\operators;

use qtism\common\datatypes\QtiBoolean;
use qtism\data\expressions\operators\Gte;

/**
 * The GteProcessor class aims at processing Gte operators.
 *
 * From IMS QTI:
 *
 * The gte operator takes two sub-expressions which must both have single cardinality
 * and have a numerical base-type. The result is a single boolean with a value of
 * true if the first expression is numerically greater than or equal to the second
 * and false if it is less than the second. If either sub-expression is NULL then
 * the operator results in NULL.
 */
class GteProcessor extends OperatorProcessor
{
    /**
     * Process the Gte operator.
     *
     * @return QtiBoolean|null Whether the first sub-expression is numerically greather than or equal to the second or NULL if either sub-expression is NULL.
     * @throws OperatorProcessingException
     */
    public function process()
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The Gte operator only accepts operands with a single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyNumeric() === false) {
            $msg = 'The Gte operator only accepts operands with a float or integer baseType.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        return new QtiBoolean($operands[0]->getValue() >= $operands[1]->getValue());
    }

    /**
     * @return string
     */
    protected function getExpressionType()
    {
        return Gte::class;
    }
}
