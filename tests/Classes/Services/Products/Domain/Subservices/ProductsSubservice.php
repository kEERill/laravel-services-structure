<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Domain\Subservices;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\CreateProductInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\GetProductByIdInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\DataTransferObjects\ProductData;

final class ProductsSubservice implements GetProductByIdInterface, CreateProductInterface
{
    /**
     * @param  ProductData  $productData
     * @return ProductData
     */
    public function createProduct(ProductData $productData): ProductData
    {
        return $productData;
    }

    /**
     * @param  int  $productId
     * @return ProductData
     */
    public function getProductById(int $productId): ProductData
    {
        return new ProductData($productId, 'Testing Product');
    }
}
