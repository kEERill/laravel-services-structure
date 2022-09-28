<?php

namespace KEERill\ServiceStructure\Services;

use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use KEERill\ServiceStructure\Actions\ActionRegistrar;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * @var ServiceConfigurator
     */
    private ServiceConfigurator $serviceConfigurator;

    /**
     * @var string
     */
    private string $servicePath;

    /**
     * @param $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->servicePath = $this->getServicePath();
    }

    /**
     * @return void
     *
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->serviceConfigurator = $this->createConfiguration();
        $this->configureService($this->serviceConfigurator);

        $this->registerAnnotationsSubservices();

        $this->registerActions($this->serviceConfigurator->getSubServices());

        if ($this->app->runningUnitTests()) {
            $this->registerActions($this->serviceConfigurator->getTestingSubService());
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
        return new ServiceConfigurator($this->servicePath);
    }

    /**
     * @param  ServiceConfigurator  $serviceConfigurator
     * @return void
     */
    abstract protected function configureService(ServiceConfigurator $serviceConfigurator): void;

    /**
     * @param  array  $actions
     * @return void
     */
    private function registerActions(array $actions): void
    {
        foreach ($actions as $contract => $subService) {
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

    /**
     * @return string
     */
    private function getSubservicesPath(): string
    {
        return $this->servicePath . DIRECTORY_SEPARATOR .'Domain' . DIRECTORY_SEPARATOR . 'Subservices';
    }

    /**
     * @return string
     */
    private function getRootNamespace(): string
    {
        return Str::replaceLast('\\' . class_basename($this), '', get_class($this));
    }

    /**
     * @return string
     */
    private function getSubservicesNamespace(): string
    {
        return $this->getRootNamespace() . '\\Domain\\Subservices';
    }

    /**
     * @return void
     *
     * @throws ReflectionException
     */
    private function registerAnnotationsSubservices(): void
    {
        $subservicesPath = $this->getSubservicesPath();
        $rootNamespace = $this->getSubservicesNamespace();

        if (is_dir($subservicesPath)) {
            $actions = $this->getActions($rootNamespace, $subservicesPath);

            config()
                ->set("services-structure.actions.$rootNamespace", $actions);

            $this->registerActions($actions);
        }
    }

    /**
     * @param string $rootNamespace
     * @param string $subservicesPath
     * @return array<class-string, class-string>
     *
     * @throws ReflectionException
     */
    private function getActions(string $rootNamespace, string $subservicesPath): array
    {
        return config()->has("services-structure.actions.$rootNamespace")
            ? config("services-structure.actions.$rootNamespace")
            : ActionRegistrar::getActionsByService($rootNamespace, $subservicesPath);
    }
}
