<?php

namespace KEERill\ServiceStructure\Tests\Classes\Services\Products\Domain\Subservices\Sub;

use KEERill\ServiceStructure\Attributes\RegisterAction;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Infrastructure\Contracts\AnnotationProductInterface;

final class AnnotationProductsSubservice
    implements AnnotationProductInterface
{
    #[RegisterAction(AnnotationProductInterface::class)]
    public function annotationProduct(): string
    {
        return 'Hello world';
    }
}
