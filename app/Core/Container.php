<?php

class Container
{
    protected array $instances = [];

    /**
     * Registered services.
     */
    protected array $services = [];

    /**
     * Bind a service to the container.
     */
    public function bind($key, $service)
    {
        $this->services[$key] = $service;
    }

    /**
     * Register a singleton service.
         */
     public function singleton($key, $service)
     {
            $this->instances[$key] = call_user_func($service);
     }

    /**
     * Resolve a service.
     */
    public function make($class)
    {
        // Return existing singleton instance
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->services[$class])) {

            return call_user_func(
                $this->services[$class]
            );

        }

        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        // No constructor? Just create the object
        if (is_null($constructor)) {

            $object = new $class();

            $this->instances[$class] = $object;

            return $object;
        }

        $parameters = $constructor->getParameters();

        $dependencies = [];

        foreach ($parameters as $parameter) {

            $type = $parameter->getType();

            // Primitive types cannot be resolved automatically
            if ($type->isBuiltin()) {

                throw new Exception(
                    "Cannot resolve primitive dependency '" .
                    $type->getName() .
                    " $" .
                    $parameter->getName() .
                    "' while resolving {$class}."
                );
            }

            // Resolve dependency recursively
            $dependencies[] = $this->make(
                $type->getName()
            );
        }

        // Create the object
        $object = new $class(...$dependencies);

        // Store singleton instance
        $this->instances[$class] = $object;

        return $object;
    }
}