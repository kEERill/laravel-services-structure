<?php

namespace KEERill\ServiceStructure\Services;

use Illuminate\Support\Arr;

final class ServiceConfigurator
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $subServices = [];

    /**
     * @var array<class-string, class-string>
     */
    protected array $testingSubService = [];

    /**
     * @var array
     */
    protected array $listeners = [];

    /**
     * @var array
     */
    protected array $commands = [];

    /**
     * @var string|null
     */
    protected string|null $migrationsNamespace = null;

    /**
     * @var string|null
     */
    protected string|null $routerFile = null;

    public function __construct(
        protected string $servicePath
    ) {
    }

    /**
     * @return array
     */
    public function getSubServices(): array
    {
        return $this->subServices;
    }

    /**
     * @return array
     */
    public function getTestingSubService(): array
    {
        return $this->testingSubService;
    }

    /**
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @return string|null
     */
    public function getMigrationsNamespace(): ?string
    {
        return $this->migrationsNamespace;
    }

    /**
     * @return string|null
     */
    public function getRouterFile(): ?string
    {
        return $this->routerFile;
    }

    /**
     * @param  array  $subServices
     * @return $this
     */
    public function usingSubservices(array $subServices): self
    {
        $subServices = array_map(
            fn ($subService) => Arr::wrap($subService),
            $subServices
        );

        foreach ($subServices as $contract => $subService) {
            $this->addSubservice($contract, $subService[0], $subService[1] ?? null);
        }

        return $this;
    }

    /**
     * @param  array  $listeners
     * @return $this
     */
    public function usingListeners(array $listeners): self
    {
        $this->listeners = $listeners;

        return $this;
    }

    /**
     * @param  array  $commands
     * @return $this
     */
    public function usingCommands(array $commands): self
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @param  string  $namespace
     * @return $this
     */
    public function usingMigrations(string $namespace = 'Application/Database/Migrations'): self
    {
        $this->migrationsNamespace = $this->servicePath.DIRECTORY_SEPARATOR.$namespace;

        return $this;
    }

    /**
     * @param  string  $file
     * @return $this
     */
    public function usingRoutes(string $file = 'routes.php'): self
    {
        $this->routerFile = $this->servicePath.DIRECTORY_SEPARATOR.$file;

        return $this;
    }

    /**
     * @param  string  $contract
     * @param  string  $subService
     * @param  string|null  $testSubService
     * @return $this
     */
    protected function addSubservice(string $contract, string $subService, string $testSubService = null): self
    {
        $this->subServices[$contract] = $subService;

        if ($testSubService !== null) {
            $this->testingSubService[$contract] = $testSubService;
        }

        return $this;
    }
}
