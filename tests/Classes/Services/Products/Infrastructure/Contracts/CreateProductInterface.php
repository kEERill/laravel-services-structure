<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\DataTransferObjects\ProductData;

interface CreateProductInterface
{
    public function createProduct(ProductData $productData): ProductData;
}
