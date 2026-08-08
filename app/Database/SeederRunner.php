<?php

class SeederRunner
{
    /**
     * Run the main database seeder.
     */
    public function run()
    {
        $file = __DIR__ .
            "/../../database/seeders/DatabaseSeeder.php";

        if (!file_exists($file)) {
            throw new Exception(
                "DatabaseSeeder.php not found."
            );
        }

        require_once $file;

        if (!class_exists("DatabaseSeeder")) {
            throw new Exception(
                "DatabaseSeeder class not found."
            );
        }

        echo "Seeding database..." . PHP_EOL;

        $seeder = new DatabaseSeeder();

        $seeder->run();

        echo "Database seeding completed." . PHP_EOL;
    }
}