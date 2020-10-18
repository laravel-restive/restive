<?php declare(strict_types=1);

namespace Restive;

use Restive\Http\Routing\ResourceRegistrar;
use Restive\Http\Routing\PendingResourceRegistration;

class Restive
{
    public function resource($name, $controller, array $options = [])
    {
        $registrar = $this->resolveRegistrar(ResourceRegistrar::class);
        $pending = new PendingResourceRegistration($registrar, $name, $controller, $options);
        return $pending;
    }

    protected function resolveRegistrar($registrarClass)
    {
        return new $registrarClass(app('router'));
    }
}
