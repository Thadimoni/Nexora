<?php

class Container
{
    /**
     * Singleton instances.
     */
    protected array $instances = [];

    /**
     * Registered services.
     */
    protected array $services = [];

    /**
     * Register a normal service.
     */
    public function bind($key, $resolver)
    {
        $this->services[$key] = $resolver;
    }

    /**
     * Register a singleton service.
     */
    public function singleton($key, $resolver)
    {
        $this->services[$key] = function () use ($key, $resolver) {

            if (!isset($this->instances[$key])) {

                $this->instances[$key] = call_user_func($resolver);

            }

            return $this->instances[$key];

        };
    }

    /**
     * Resolve a service.
     */
    public function make($class)
    {
        /*
        |--------------------------------------------------------------------------
        | Existing Singleton
        |--------------------------------------------------------------------------
        */
        if (isset($this->instances[$class])) {

            return $this->instances[$class];

        }

        /*
        |--------------------------------------------------------------------------
        | Registered Binding
        |--------------------------------------------------------------------------
        */
        if (isset($this->services[$class])) {

            return call_user_func(
                $this->services[$class]
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Auto Resolve
        |--------------------------------------------------------------------------
        */
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {

            throw new Exception(
                "{$class} cannot be instantiated."
            );

        }

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {

            return new $class();

        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type === null || $type->isBuiltin()) {

                throw new Exception(
                    "Cannot resolve dependency {$parameter->getName()} while building {$class}."
                );

            }

            $dependencies[] = $this->make(
                $type->getName()
            );
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}