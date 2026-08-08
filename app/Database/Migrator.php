<?php

class Migrator
{
    protected Database $db;
    protected mysqli $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    /**
     * Run all pending migrations.
     */
    public function migrate()
    {
        $this->createMigrationTable();
        $batch = $this->getNextBatch();

        $files = glob(
            __DIR__ . "/../../database/migrations/*.php"
        );

        sort($files);

        foreach ($files as $file) {

            require_once $file;

            $class = $this->getClassName($file);

            if (!$class) {
                continue;
            }

            if ($this->hasRun(basename($file))) {
                continue;
            }

            echo "Migrating: " . basename($file) . PHP_EOL;

            $migration = new $class();

            $migration->up();

            $this->recordMigration(
            basename($file),
            $batch
            );

            echo "Done." . PHP_EOL;
        }

        echo PHP_EOL;
        echo "All migrations completed." . PHP_EOL;
    }

    /**
     * Create migrations table.
     */
    protected function createMigrationTable()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS migrations (

            id INT AUTO_INCREMENT PRIMARY KEY,

            migration VARCHAR(255) NOT NULL,

            batch INT NOT NULL,

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

        )
        ";

        $this->conn->query($sql);
    }

    /**
     * Check if migration already ran.
     */
    protected function hasRun($migration)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM migrations
             WHERE migration=? LIMIT 1"
        );

        $stmt->bind_param("s", $migration);

        $stmt->execute();

        return $stmt
            ->get_result()
            ->num_rows > 0;
    }

    /**
     * Record migration.
     */
    protected function recordMigration($migration, $batch)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO migrations
            (migration, batch)
            VALUES (?, ?)"
        );

        $stmt->bind_param(
            "si",
            $migration,
            $batch
        );

        $stmt->execute();
    }

    /**
     * Get class name from filename.
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

    /**
 * Get the next migration batch number.
 */
protected function getNextBatch()
{
    $result = $this->conn->query(
        "SELECT MAX(batch) AS batch FROM migrations"
    );

    $row = $result->fetch_assoc();

    return ((int) ($row["batch"] ?? 0)) + 1;
}

}