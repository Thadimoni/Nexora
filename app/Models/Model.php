<?php

class Model
{
    protected $db;
    protected $conn;

    protected $table;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    /**
     * Get all records
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";

        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Find by ID
     */
    public function find($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Delete Record
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    /**
     * Find First Record By Column
     */
    public function where($column, $value)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE {$column}=? LIMIT 1"
        );

        $stmt->bind_param("s", $value);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}