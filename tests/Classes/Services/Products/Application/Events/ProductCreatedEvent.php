<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Events;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\DataTransferObjects\ProductData;

final class ProductCreatedEvent
{
    public function __construct(
        public ProductData $product
    ) {}
}
