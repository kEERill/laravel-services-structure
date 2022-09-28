<?php

namespace KEERill\ServiceStructure\Actions;

use ReflectionException;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

final class ActionRegistrar
{
    /**
     * @param string $rootNamespace
     * @param string $subservicesPath
     * @return void
     */
    public function __construct(
        protected string $rootNamespace,
        protected string $subservicesPath
    ) {}

    /**
     * @return array<class-string, class-string>
     *
     * @throws ReflectionException
     */
    public function actions(): array
    {
        $actions = [];

        $files = (new Finder())
            ->files()
            ->name('*.php')
            ->in($this->subservicesPath)
            ->sortByName();

        foreach ($files as $file) {
            $class = $this->getClassByPath($file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());
            $actions = array_merge($actions, ClassActionAttribute::getActionsByClass($class));
        }

        return $actions;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getClassByPath(string $path): string
    {
        $class = Str::of($path)
            ->replaceFirst($this->subservicesPath, '')
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->replaceLast('.php', '');

        return $this->rootNamespace . $class;
    }

    /**
     * @param string $rootNamespace
     * @param string $subservicesPath
     * @return array
     *
     * @throws ReflectionException
     */
    public static function getActionsByService(string $rootNamespace, string $subservicesPath): array
    {
        return (new ActionRegistrar($rootNamespace, $subservicesPath))
            ->actions();
    }
}
