<?php

namespace Voyager\Database;

abstract class DBResponse
{
    /**
     * Store connection object.
     * 
     * @var object
     */

    protected $conn;

    /**
     * CRUD operation.
     * 
     * @var string
     */

    private $operation;

    /**
     * SQL query string.
     * 
     * @var string
     */

    private $sql;

    /**
     * Execution start time.
     * 
     * @var float
     */

    private $start_time;

    /**
     * Execution end time.
     * 
     * @var float
     */

    private $end_time;

    /**
     * Query succeed.
     * 
     * @var bool
     */

    private $success;

    /**
     * Create new database response object.
     * 
     * @param   object $conn
     * @param   string $operation
     * @param   string $sql
     * @param   float $start_time
     * @param   float $end_time
     * @param   bool $success
     * @return  void
     */

    public function __construct($conn, string $operation, string $sql, float $start_time, float $end_time, bool $success = false)
    {
        $this->conn = $conn;
        $this->operation = $operation;
        $this->sql = $sql;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->success = $success;
    }

    /**
     * Return true if query succeed.
     * 
     * @return  bool
     */

    public function success()
    {
        return $this->success;
    }

    /**
     * Return crud operation used.
     * 
     * @return  string
     */

    public function operation()
    {
        return $this->operation;
    }

    /**
     * Return executed sql query.
     * 
     * @return  string
     */

    public function sql()
    {
        return $this->sql;
    }

    /**
     * Return how long the query is executed.
     * 
     * @return  string
     */

    public function executionTime()
    {
        return (string)number_format($this->end_time - $this->start_time, 4);
    }

    /**
     * Return how many rows the query affected.
     * 
     * @return int
     */

    public function affectedRows()
    {
        return (int)$this->conn->affectedRows();
    }

}