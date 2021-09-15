<?php

namespace qti\customOperators;

use qtism\common\enums\BaseType;
use qtism\runtime\common\MultipleContainer;

class CsvToMultiple extends CsvToContainer
{
    protected function createContainer()
    {
        return new MultipleContainer(BaseType::STRING);
    }
}
