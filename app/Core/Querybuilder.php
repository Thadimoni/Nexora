<?php

class QueryBuilder
{
    protected Database $db;
    protected mysqli $conn;

    protected string $table;

    protected string $select = "*";

    protected array $wheres = [];

    protected array $bindings = [];

    protected string $orderBy = "";

    protected string $limitClause = "";

    public function __construct($table)
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
        $this->table = $table;
    }

    /**
     * Add WHERE clause
     *
     * Examples:
     * where("id", 1)
     * where("id", ">", 10)
     * where("name", "LIKE", "%John%")
     */
    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Order results
     */
    public function orderBy($column, $direction = "ASC")
    {
        $this->orderBy = " ORDER BY {$column} {$direction}";

        return $this;
    }

    /**
     * Limit results
     */
    public function limit($limit)
    {
        $this->limitClause = " LIMIT {$limit}";

        return $this;
    }

    /**
     * Build SQL query
     */
    protected function buildQuery()
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(" AND ", $this->wheres);
        }

        $sql .= $this->orderBy;
        $sql .= $this->limitClause;

        return $sql;
    }

    /**
     * Determine binding types automatically
     */
    protected function getBindingTypes()
    {
        $types = "";

        foreach ($this->bindings as $binding) {

            if (is_int($binding)) {

                $types .= "i";

            } elseif (is_float($binding)) {

                $types .= "d";

            } else {

                $types .= "s";

            }
        }

        return $types;
    }

    /**
     * Execute query and return all results
     */
    public function get()
    {
        $sql = $this->buildQuery();

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        if (!empty($this->bindings)) {

            $types = $this->getBindingTypes();

            $stmt->bind_param(
                $types,
                ...$this->bindings
            );
        }

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Return first result
     */
    public function first()
    {
        $this->limit(1);

        $result = $this->get();

        return $result[0] ?? null;
    }

    /**
     * Insert a new record
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

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $bindings = array_values($data);

        $this->bindings = $bindings;

        $types = $this->getBindingTypes();

        $stmt->bind_param(
            $types,
            ...$bindings
        );

        $stmt->execute();

        return $this->conn->insert_id;
    }

    /**
     * Update a record
     */
    public function update($id, array $data)
    {
        $columns = [];

        foreach ($data as $column => $value) {
            $columns[] = "{$column} = ?";
        }

        $sql = "UPDATE {$this->table}
                SET " . implode(", ", $columns) . "
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $bindings = array_values($data);
        $bindings[] = $id;

        $this->bindings = $bindings;

        $types = $this->getBindingTypes();

        $stmt->bind_param(
            $types,
            ...$bindings
        );

        return $stmt->execute();
    }

    /**
     * Delete a record
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM {$this->table} WHERE id = ?"
        );

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    /**
     * Count records
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";

        $result = $this->conn
            ->query($sql)
            ->fetch_assoc();

        return (int) $result["total"];
    }

    /**
     * Determine whether records exist
     */
    public function exists()
    {
        return $this->count() > 0;
    }
}