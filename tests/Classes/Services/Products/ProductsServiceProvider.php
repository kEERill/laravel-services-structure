<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products;

use KEERill\ServiceStructure\Services\AbstractServiceProvider;
use KEERill\ServiceStructure\Services\ServiceConfigurator;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Events\ProductCreatedEvent;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Listeners\WorkWhenProductCreatedListener;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Domain\Subservices\CreateProductsTestSubservice;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Domain\Subservices\ProductsSubservice;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\CreateProductInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\GetProductByIdInterface;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Interfaces\Console\ProductsTestCommand;

final class ProductsServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected array $subServices = [
        CreateProductInterface::class => [
            ProductsSubservice::class,
            CreateProductsTestSubservice::class,
        ],

        GetProductByIdInterface::class => ProductsSubservice::class,
    ];

    /**
     * @var array
     */
    protected array $listeners = [
        ProductCreatedEvent::class => [
            WorkWhenProductCreatedListener::class,
        ],
    ];

    /**
     * @var class-string[]
     */
    protected array $commands = [
        ProductsTestCommand::class,
    ];

    /**
     * @param  ServiceConfigurator  $serviceConfigurator
     * @return void
     */
    protected function configureService(ServiceConfigurator $serviceConfigurator): void
    {
        $serviceConfigurator
            ->usingSubservices($this->subServices)
            ->usingListeners($this->listeners)
            ->usingCommands($this->commands)
            ->usingMigrations()
            ->usingRoutes();
    }
}
