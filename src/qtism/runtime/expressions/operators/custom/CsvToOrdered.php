<?php

namespace qti\customOperators;

use qtism\common\enums\BaseType;
use qtism\runtime\common\OrderedContainer;

class CsvToOrdered extends CsvToContainer
{
    protected function createContainer()
    {
        return new OrderedContainer(BaseType::STRING);
    }
}
