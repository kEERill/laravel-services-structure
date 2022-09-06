<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\DataTransferObjects\ProductData;

interface GetProductByIdInterface
{
    /**
     * @param  int  $productId
     * @return ProductData
     */
    public function getProductById(int $productId): ProductData;
}
