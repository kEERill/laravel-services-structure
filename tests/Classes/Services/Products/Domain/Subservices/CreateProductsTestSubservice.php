<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Domain\Subservices;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Exceptions\NotAllowedTestingException;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\CreateProductInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\DataTransferObjects\ProductData;

final class CreateProductsTestSubservice
    implements CreateProductInterface
{
    /**
     * @param ProductData $productData
     * @return ProductData
     *
     * @throws NotAllowedTestingException
     */
    public function createProduct(ProductData $productData): ProductData
    {
        throw new NotAllowedTestingException('Нельзя создавать продукты в тестировании');
    }
}
