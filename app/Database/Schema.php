<?php

class Schema
{
    /**
     * Create a table.
     */
    public static function create(
        string $table,
        callable $callback
    )
    {
        $blueprint = new Blueprint($table);

        // Build the table definition
        $callback($blueprint);

        $columns = implode(
            ", ",
            $blueprint->getColumns()
        );

        $sql = "CREATE TABLE IF NOT EXISTS {$table} ({$columns})";

        $db = new Database();

        $conn = $db->connect();

        if (!$conn->query($sql)) {

            throw new Exception(
                "Migration Error: " .
                $conn->error
            );
        }

        echo "Table '{$table}' created successfully." . PHP_EOL;
    }

    /**
     * Drop a table.
     */
    public static function drop(string $table)
    {
        $db = new Database();

        $conn = $db->connect();

        $sql = "DROP TABLE IF EXISTS {$table}";

        if (!$conn->query($sql)) {

            throw new Exception(
                "Migration Error: " .
                $conn->error
            );
        }

        echo "Table '{$table}' dropped successfully." . PHP_EOL;
    }
}