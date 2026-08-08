<?php

class MakeModelCommand
{
    protected FileGenerator $generator;

    public function __construct()
    {
        $this->generator = new FileGenerator();
    }

    public function handle(?string $name)
    {
        if (!$name) {
            echo "Usage: php nexora make:model ModelName" . PHP_EOL;
            return;
        }

        $class = ucfirst($name);

        $this->generator->generate(
            "model.stub",
            $class,
            __DIR__ . "/../../Models/"
        );

        echo "Model {$class} created successfully!" . PHP_EOL;
    }
}