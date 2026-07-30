<?php

abstract class BaseModel
{
    protected Database $database;

    protected string $table;

    public function __construct()
    {
        $this->database = new Database();
    }

    /**
     * Find record by ID
     */
    public function find($id)
    {
        return $this->database->first(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    /**
     * Get all records
     */
    public function all()
    {
        return $this->database->get(
            "SELECT * FROM {$this->table}"
        );
    }

    /**
     * Find one record by column
     */
    public function where($column, $value)
    {
        return $this->database->first(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }

    /**
     * Create a new record
     */
    public function create(array $data)
    {
        $columns = array_keys($data);

        $placeholders = array_fill(
            0,
            count($columns),
            "?"
        );

        $sql = "INSERT INTO {$this->table} (" .
                implode(", ", $columns) .
                ") VALUES (" .
                implode(", ", $placeholders) .
                ")";

        $this->database->execute(
            $sql,
            array_values($data)
        );

        return $this->database->lastInsertId();
    }

    /**
     * Update record
     */
    public function update($id, array $data)
    {
        $columns = [];

        foreach ($data as $key => $value) {
            $columns[] = "{$key} = ?";
        }

        $sql = "UPDATE {$this->table}
                SET " . implode(", ", $columns) . "
                WHERE id = ?";

        $params = array_values($data);
        $params[] = $id;

        return $this->database->execute($sql, $params);
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        return $this->database->execute(
            "DELETE FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    /**
     * Count records
     */
    public function count()
    {
        $result = $this->database->first(
            "SELECT COUNT(*) AS total FROM {$this->table}"
        );

        return $result['total'];
    }
}