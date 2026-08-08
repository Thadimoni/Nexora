<?php

class MakeControllerCommand
{
    protected FileGenerator $generator;

    public function __construct()
    {
        $this->generator = new FileGenerator();
    }

    public function handle(?string $name)
    {
        if (!$name) {
            echo "Controller name is required." . PHP_EOL;
            return;
        }

        $this->generator->generate(
            "controller.stub",
            $name,
            __DIR__ . "/../../Http/Controllers/"
        );

        echo "Controller {$name} created successfully!" . PHP_EOL;
    }
}