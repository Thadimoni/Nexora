<?php

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "Running database seeders..." . PHP_EOL;

        $student = new Student();

        $existing = $student
            ->where("email", "john@example.com")
            ->first();

        if ($existing) {
            echo "Student already exists. Skipping." . PHP_EOL;
            return;
        }

        $student->create([
            "fullname" => "John Doe",
            "email" => "john@example.com"
        ]);

        echo "Student seeded successfully." . PHP_EOL;
    }
}