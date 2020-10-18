<?php declare(strict_types=1);

namespace Restive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Restive\Exceptions\InvalidModelException;
use Restive\Http\Requests\Request;

class ComponentFactory
{
    protected $modelNamespacePrefix = '';
    protected $resourceNamespacePrefix = '';
    protected $resourceCollectionNamespacePrefix = '';
    protected $requestNamespacePrefix = '';

    public function resolveModel(string $modelName) : Model
    {
        if (empty($modelName)) {
            throw new InvalidModelException();
        }
        if (class_exists(ucfirst($modelName), true)) {
            $className = ucfirst($modelName);
            return new $className;
        }
        if (class_exists($this->modelNamespacePrefix . '\\App\\' . ucfirst($modelName), true)) {
            $className = $this->modelNamespacePrefix . '\\App\\' . ucfirst($modelName);
            return new $className;
        }
        if (class_exists($this->modelNamespacePrefix . '\\App\\Models\\' . ucfirst($modelName), true)) {
            $className = $this->modelNamespacePrefix . '\\App\\Models\\' . ucfirst($modelName);
            return new $className;
        }
        throw new InvalidModelException();
    }

    public function resolveResource($model, string $resource)
    {
        if (!empty($resource)) {
            return $resource;
        }
        $model = class_basename($model);
        $resourceName = $model . 'Resource';
        if (class_exists($this->resourceNamespacePrefix . '\\App\\Http\\Resources\\' . $resourceName, true)) {
            $className = $this->resourceNamespacePrefix . '\\App\\Http\\Resources\\' . $resourceName;
            return $className;
        }
        return \Restive\Http\Resources\Resource::class;
    }

    public function resolveResourceCollection($model, string $resourceCollection)
    {
        if (!empty($resourceCollection)) {
            return $resourceCollection;
        }
        $model = class_basename($model);
        $resourceName = $model . 'CollectionResource';
        if (class_exists($this->resourceCollectionNamespacePrefix . '\\App\\Http\\Resources\\' . $resourceName, true)) {
            $className = $this->resourceCollectionNamespacePrefix . '\\App\\Http\\Resources\\' . $resourceName;
            return $className;
        }
        return \Restive\Http\Resources\CollectionResource::class;
    }

    public function resolveRequest($model, string $request)
    {
        if (!empty($request)) {
            return $request;
        }
        $model = class_basename($model);
        $requestName = $model . 'Request';
        if (class_exists($this->requestNamespacePrefix . '\\App\\Http\\Requests\\' . $requestName, true)) {
            $className = $this->requestNamespacePrefix . '\\App\\Http\\Requests\\' . $requestName;
            return $className;
        }
        return \Restive\Http\Requests\Request::class;
    }

    public function resolvePaginator(string $paginator)
    {
        if (!empty($paginator)) {
            return $paginator;
        }
        return \Restive\Paginator::class;
    }

    public function setModelNamespacePrefix($prefix)
    {
        $this->modelNamespacePrefix = $prefix;
    }

    public function setResourceNamespacePrefix($prefix)
    {
        $this->resourceNamespacePrefix = $prefix;
    }

    public function setResourceCollectionNamespacePrefix($prefix)
    {
        $this->resourceCollectionNamespacePrefix = $prefix;
    }

    public function setRequestNamespacePrefix($prefix)
    {
        $this->requestNamespacePrefix = $prefix;
    }

    public function bindRequestClass(string $requestClass): void
    {
        App::bind(Request::class, $requestClass);
    }

}