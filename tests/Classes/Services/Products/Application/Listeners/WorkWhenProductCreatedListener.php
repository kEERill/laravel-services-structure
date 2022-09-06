<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Listeners;

use KEERill\ServiceStructure\Tests\Classes\Services\Products\Application\Events\ProductCreatedEvent;

final class WorkWhenProductCreatedListener
{
    public function handle(ProductCreatedEvent $event): void {}
}
