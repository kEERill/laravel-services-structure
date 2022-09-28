<?php

namespace KEERill\ServiceStructure\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class RegisterAction
{
    /**
     * @param string $interfaceClass
     * @return void
     */
    public function __construct(
        public string $interfaceClass
    ){}
}
