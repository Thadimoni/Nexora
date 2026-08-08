<?php

class MakeMigrationCommand
{
    protected FileGenerator $generator;

    public function __construct()
    {
        $this->generator = new FileGenerator();
    }

    public function handle(?string $name)
    {
        if (!$name) {
            echo "Usage: php nexora make:migration MigrationName" . PHP_EOL;
            return;
        }

        $timestamp = date("YmdHis");

        $filename = $timestamp . "_" . $name;

        $class = str_replace(
            " ",
            "",
            ucwords(
                str_replace("_", " ", $name)
            )
        );

        $this->generator->generate(
            "migration.stub",
            $class,
            __DIR__ . "/../../../database/migrations/",
            $filename
        );

        echo "Migration {$filename}.php created successfully." . PHP_EOL;
    }
}