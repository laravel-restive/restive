<?php

namespace Restive;

use Illuminate\Contracts\Container\BindingResolutionException;
use Restive\Routing\ResourceRegistrar;
use Restive\Routing\PendingResourceRegistration;

class Restive
{
    /**
     * Registers new standard resource.
     *
     * @param string $name
     * @param string $controller
     * @param array $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     * @throws BindingResolutionException
     */
    public function resource($name, $controller, $options = [])
    {
        $registrar = $this->resolveRegistrar(ResourceRegistrar::class);
        $pending = new PendingResourceRegistration($registrar, $name, $controller, $options);
        return $pending;
    }

    /**
     * Retrieves resource registrar from the container.
     *
     * @param string $registrarClass
     * @return ResourceRegistrar
     * @throws BindingResolutionException
     */
    protected function resolveRegistrar($registrarClass)
    {
        if (app()->bound($registrarClass)) {
            return app()->make($registrarClass);
        }

        return new $registrarClass(app('router'));
    }
}
