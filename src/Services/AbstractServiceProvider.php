<?php

namespace KEERill\ServiceStructure\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * @var ServiceConfigurator
     */
    private ServiceConfigurator $serviceConfigurator;

    /**
     * @return void
     */
    public function register(): void
    {
        $this->serviceConfigurator = $this->createConfiguration();
        $this->configureService($this->serviceConfigurator);

        $this->registerSubservices($this->serviceConfigurator->getSubServices());

        if ($this->app->runningUnitTests()) {
            $this->registerSubservices($this->serviceConfigurator->getTestingSubService());
        }
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->serviceConfigurator->getMigrationsNamespace() !== null) {
            $this->loadMigrationsFrom($this->serviceConfigurator->getMigrationsNamespace());
        }

        if ($this->serviceConfigurator->getRouterFile()) {
            $this->loadRoutesFrom($this->serviceConfigurator->getRouterFile());
        }

        if ($this->app->runningInConsole()) {
            $this->commands($this->serviceConfigurator->getCommands());
        }

        $config = $this->serviceConfigurator
            ->getConfig();

        if ($config != null) {
            $this->mergeConfigFrom($this->serviceConfigurator->getConfigFile(), "services.$config");
        }

        $this->registerListeners($this->serviceConfigurator->getListeners());
    }

    /**
     * @return ServiceConfigurator
     */
    private function createConfiguration(): ServiceConfigurator
    {
        return new ServiceConfigurator($this->getServicePath());
    }

    /**
     * @param  ServiceConfigurator  $serviceConfigurator
     * @return void
     */
    abstract protected function configureService(ServiceConfigurator $serviceConfigurator): void;

    /**
     * @param  array  $configureSubServices
     * @return void
     */
    private function registerSubservices(array $configureSubServices): void
    {
        foreach ($configureSubServices as $contract => $subService) {
            $this->app->bind($contract, $subService);
        }
    }

    /**
     * @param  array  $configureListeners
     * @return void
     */
    private function registerListeners(array $configureListeners): void
    {
        foreach ($configureListeners as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * @return string
     */
    private function getServicePath(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return dirname($reflector->getFileName());
    }
}
