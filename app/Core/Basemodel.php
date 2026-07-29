<?php

abstract class BaseModel
{
    protected Database $database;

    protected string $table;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function find($id)
    {
        return $this->database->first(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    public function all()
    {
        return $this->database->get(
            "SELECT * FROM {$this->table}"
        );
    }

    public function where($column, $value)
    {
        return $this->database->first(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }

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
}