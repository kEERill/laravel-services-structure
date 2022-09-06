<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Interfaces\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\GetProductByIdInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Interfaces\Http\Resources\ProductDataResource;

final class ProductsController
{
    /**
     * @param GetProductByIdInterface $subservice
     * @param int $productId
     * @return JsonResource
     */
    public function get(GetProductByIdInterface $subservice, int $productId): JsonResource
    {
        return new ProductDataResource($subservice->getProductById($productId));
    }
}
