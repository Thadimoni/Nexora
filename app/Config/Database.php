<?php

class Database
{
    private string $host = "localhost";
    private string $dbname = "nexora";
    private string $username = "root";
    private string $password = "";

    private mysqli $connection;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {

            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->dbname
            );

            $this->connection->set_charset("utf8mb4");

        } catch (Exception $e) {

            die("Database Error: " . $e->getMessage());

        }
    }

    /**
     * Execute a prepared query.
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);

        if (!empty($params)) {

            $types = $this->getParameterTypes($params);

            $references = $this->makeReferences($params);

            $arguments = array_merge(
                [$types],
                $references
            );

            call_user_func_array(
                [$stmt, "bind_param"],
                $arguments
            );
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * Return one record.
     */
    public function first($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    /**
     * Return all records.
     */
    public function get($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Execute INSERT/UPDATE/DELETE.
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);

        return $stmt->affected_rows;
    }

    /**
     * Last inserted ID.
     */
    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

    /**
     * Determine bind_param() types.
     */
    private function getParameterTypes(array $params)
    {
        $types = "";

        foreach ($params as $param) {

            if (is_int($param)) {

                $types .= "i";

            } elseif (is_float($param)) {

                $types .= "d";

            } elseif (is_string($param)) {

                $types .= "s";

            } else {

                $types .= "b";

            }

        }

        return $types;
    }

    /**
     * Convert values into references.
     */
    private function makeReferences(array &$params)
    {
        $references = [];

        foreach ($params as $key => $value) {

            $references[$key] = &$params[$key];

        }

        return $references;
    }
}