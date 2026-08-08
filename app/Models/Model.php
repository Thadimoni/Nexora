<?php

abstract class Model
{
    /*
    |--------------------------------------------------------------------------
    | Model Properties
    |--------------------------------------------------------------------------
    */

    protected Database $db;

    protected mysqli $conn;

    protected string $table;

    protected ?int $id = null;

    protected array $attributes = [];

    protected QueryBuilder $query;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->db = new Database();

        $this->conn = $this->db->connect();

        $this->query = new QueryBuilder($this->table);
    }


    /*
    |--------------------------------------------------------------------------
    | Attribute Access
    |--------------------------------------------------------------------------
    */

    /**
     * Access model attributes.
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $this->$key ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | Query Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get all records.
     */
    public function all()
    {
        $results = $this->query->get();

        return array_map(
            fn($result) => $this->hydrate($result),
            $results
        );
    }


    /**
     * Find a record by ID.
     */
    public function find($id)
    {
        $result = $this->query
            ->where("id", $id)
            ->first();

        if (!$result) {
            return null;
        }

        return $this->hydrate($result);
    }


    /**
     * Add a WHERE condition.
     */
    public function where($column, $operator, $value = null)
    {
        $this->query->where(
            $column,
            $operator,
            $value
        );

        return $this;
    }


    /**
     * Order results.
     */
    public function orderBy($column, $direction = "ASC")
    {
        $this->query->orderBy(
            $column,
            $direction
        );

        return $this;
    }


    /**
     * Limit results.
     */
    public function limit($limit)
    {
        $this->query->limit($limit);

        return $this;
    }


    /**
     * Get query results.
     */
    public function get()
    {
        $results = $this->query->get();

        return array_map(
            fn($result) => $this->hydrate($result),
            $results
        );
    }


    /**
     * Get the first result.
     */
    public function first()
    {
        $result = $this->query->first();

        if (!$result) {
            return null;
        }

        return $this->hydrate($result);
    }


    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    /**
     * Create a new record.
     */
    public function create(array $data)
    {
        return $this->query->create($data);
    }


    /**
     * Update a record.
     */
    public function update($id, array $data)
    {
        return $this->query->update(
            $id,
            $data
        );
    }


    /**
     * Delete a record.
     */
    public function delete($id)
    {
        return $this->query->delete($id);
    }


    /*
    |--------------------------------------------------------------------------
    | Model Hydration
    |--------------------------------------------------------------------------
    */

    /**
     * Convert database attributes into a model instance.
     */
    protected function hydrate(array $attributes)
    {
        $model = new static();

        $model->attributes = $attributes;

        if (isset($attributes["id"])) {
            $model->id = (int) $attributes["id"];
        }

        return $model;
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get related records.
     *
     * Example:
     * $student->hasMany(ExamAttempt::class, "student_id");
     */
    public function hasMany($model, $foreignKey)
    {
        $instance = new $model();

        return $instance
            ->where($foreignKey, $this->id)
            ->get();
    }


    /**
     * Get the parent model.
     *
     * Example:
     * $attempt->belongsTo(Student::class, "student_id");
     */
    public function belongsTo($model, $foreignKey)
    {
        $instance = new $model();

        return $instance->find(
            $this->$foreignKey
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Count records.
     */
    public function count()
    {
        return $this->query->count();
    }


    /**
     * Determine whether records exist.
     */
    public function exists()
    {
        return $this->query->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Static ORM API
    |--------------------------------------------------------------------------
    */

    public static function __callStatic($method, $arguments)
    {
        $instance = new static();

        return $instance->$method(...$arguments);
    }
}