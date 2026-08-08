<?php

class Console
{
    public static function run(array $argv)
    {
        $command = $argv[1] ?? "help";
        echo "Command received: '{$command}'" . PHP_EOL;

    switch ($command) {

    case "help":
        self::help();
        break;

    case "make:model":
        $name = $argv[2] ?? null;
        (new MakeModelCommand())->handle($name);
        break;

    case "make:controller":
        $name = $argv[2] ?? null;
        (new MakeControllerCommand())->handle($name);
        break;
    case "make:migration":

    (new MakeMigrationCommand())
        ->handle($argv[2] ?? null);

    break;

    case "migrate":

    (new Migrator())->migrate();

    break;

    case "rollback":

    (new Rollback())->rollback();

    break;
    
    case "seed":

    (new SeederRunner())->run();

    break;

    default:
        echo "Unknown command: {$command}" . PHP_EOL;
        break;
}
    }   

    protected static function help()
    {
        echo PHP_EOL;
        echo "Nexora Framework CLI" . PHP_EOL;
        echo "=====================" . PHP_EOL . PHP_EOL;

        echo "Available Commands" . PHP_EOL . PHP_EOL;

        echo "help" . PHP_EOL;
        echo "make:model" . PHP_EOL;
        echo "make:controller" . PHP_EOL;
        echo "make:migration" . PHP_EOL;
        echo "migrate" . PHP_EOL;
        echo "rollback" . PHP_EOL;
        echo "seed" . PHP_EOL;

        echo PHP_EOL;
    }
}