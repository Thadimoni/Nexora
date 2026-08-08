<?php

class Blueprint
{
    /**
     * Table name
     */
    protected string $table;

    /**
     * Generated SQL columns
     */
    protected array $columns = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Auto Increment Primary Key
     */
    public function id()
    {
        $this->columns[] =
            "id INT AUTO_INCREMENT PRIMARY KEY";
    }

    /**
     * VARCHAR column
     */
    public function string(
        string $name,
        int $length = 255
    )
    {
        $this->columns[] =
            "{$name} VARCHAR({$length})";
    }

    /**
     * INTEGER column
     */
    public function integer(string $name)
    {
        $this->columns[] =
            "{$name} INT";
    }

    /**
     * BOOLEAN column
     */
    public function boolean(string $name)
    {
        $this->columns[] =
            "{$name} BOOLEAN";
    }

    /**
     * TIMESTAMPS
     */
    public function timestamps()
    {
        $this->columns[] =
            "created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP";

        $this->columns[] =
            "updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }

    /**
     * Return generated SQL
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}