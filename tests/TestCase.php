<?php

namespace KEERill\ServiceStructure\Tests;

use KEERill\ServiceStructure\ServiceStructureServiceProvider;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\ProductsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceStructureServiceProvider::class,
            ProductsServiceProvider::class,
        ];
    }
}
