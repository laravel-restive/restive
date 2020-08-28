<?php

namespace Restive\Routing;

class ResourceRegistrar extends \Illuminate\Routing\ResourceRegistrar
{
    /**
     * Add the destroy method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceDestroy($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.$base.'?}/';
        $action = $this->getResourceAction($name, $controller, 'destroy', $options);
        return $this->router->delete($uri, $action);
    }

    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.$base.'?}/';
        $action = $this->getResourceAction($name, $controller, 'update', $options);
        return $this->router->put($uri, $action);
    }
}