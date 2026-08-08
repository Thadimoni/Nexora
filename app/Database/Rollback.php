<?php

class Rollback
{
    protected Database $db;
    protected mysqli $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    /**
     * Rollback the latest migration batch.
     */
    public function rollback()
    {
        $batch = $this->getLastBatch();

        if (!$batch) {
            echo "Nothing to rollback." . PHP_EOL;
            return;
        }

        echo "Rolling back batch {$batch}..." . PHP_EOL;

        $migrations = $this->getMigrations($batch);

        foreach ($migrations as $migration) {

            $file = $migration["migration"];

            echo "Rolling back: {$file}" . PHP_EOL;

            $path = __DIR__ .
                "/../../database/migrations/" .
                $file;

            if (!file_exists($path)) {
                echo "Migration file not found: {$file}" . PHP_EOL;
                continue;
            }

            require_once $path;

            $class = $this->getClassName($file);

            if (!$class || !class_exists($class)) {
                echo "Migration class not found: {$file}" . PHP_EOL;
                continue;
            }

            $instance = new $class();

            $instance->down();

            $this->deleteMigration($file);

            echo "Rolled back: {$file}" . PHP_EOL;
        }

        echo "Rollback completed." . PHP_EOL;
    }

    /**
     * Get the latest batch number.
     */
    protected function getLastBatch()
    {
        $result = $this->conn->query(
            "SELECT MAX(batch) AS batch FROM migrations"
        );

        $row = $result->fetch_assoc();

        return (int) ($row["batch"] ?? 0);
    }

    /**
     * Get migrations belonging to a batch.
     */
    protected function getMigrations($batch)
    {
        $stmt = $this->conn->prepare(
            "SELECT migration
             FROM migrations
             WHERE batch = ?
             ORDER BY id DESC"
        );

        $stmt->bind_param("i", $batch);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Delete migration history.
     */
    protected function deleteMigration($migration)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM migrations
             WHERE migration = ?"
        );

        $stmt->bind_param("s", $migration);

        $stmt->execute();
    }

    /**
     * Convert migration filename to class name.
     */
    protected function getClassName($file)
    {
        $name = basename($file, ".php");

        $parts = explode("_", $name);

        array_shift($parts);

        return str_replace(
            " ",
            "",
            ucwords(
                implode(" ", $parts)
            )
        );
    }
}