<?php

namespace Voyager\Database;

use PDO;
use PDOException;
use Voyager\Facade\Str;

class PDOAdapter
{
    /**
     * Store PDO adapter string.
     * 
     * @var string
     */

    private $dsn;

    /**
     * Store database username.
     * 
     * @var string
     */

    private $username;

    /**
     * Store database password.
     * 
     * @var string
     */

    private $password;

    /**
     * Store PDO object.
     * 
     * @var \PDO
     */

    private $conn;

    /**
     * If connection has established.
     * 
     * @var bool
     */

    private $connected = false;

    /**
     * Store PDO error message.
     * 
     * @var string
     */

    private $error;

    /**
     * Store query result.
     * 
     * @var mixed
     */

    private $query;

    /**
     * Create new PDOAdapter instance.
     * 
     * @param   string $host
     * @return  void
     */

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }

    /**
     * Make database connection.
     * 
     * @return  \PDO
     */

    private function connect()
    {
        if(is_null($this->conn))
        {
            try
            {
                $pdo = new PDO($this->dsn, $this->username, $this->password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connected = true;
                $this->conn = $pdo;
            }
            catch(PDOException $e)
            {
                $this->error = $e->getMessage();
                abort(503);
            }
        }
    }

    /**
     * Return true if database is connected.
     * 
     * @return bool
     */

    public function connected()
    {
        return $this->connected;
    }

    /**
     * Return PDO error message.
     * 
     * @return  string
     */

    public function error()
    {
        return $this->error;
    }

    /**
     * Execute an SQL query.
     * 
     * @param   string $query
     * @return  mixed
     */

    public function query(string $query)
    {
        $operation = strtolower(Str::break($query, ' ')[0]);
        $success = false;

        $start_time = microtime(true);
        $this->query = $this->conn->query($query);
        $end_time = microtime(true);

        if(is_null($this->error))
        {
            $success = true;

            if(is_string($this->query))
            {
                return $this->query;
            }
        }

        if($operation === 'select' || $operation === 'show')
        {
            return new DBResult($this, $operation, $query, $start_time, $end_time, $success);
        }
        else
        {
            return new DBGenericResult($this, $operation, $query, $start_time, $end_time, $success);
        }
    }

    /**
     * Return fetched results.
     * 
     * @return array
     */

    public function fetch()
    {
        if($this->numRows() === 1)
        {
            return [$this->query->fetch(PDO::FETCH_ASSOC)];
        }
        else
        {
            $result = [];

            while($row = $this->query->fetch(PDO::FETCH_ASSOC))
            {
                $result[] = $row;
            }

            return $result;
        }
    }

    /**
     * Return number of rows from result.
     * 
     * @return int
     */

    public function numRows()
    {
        return $this->query->rowCount();
    }

    /**
     * Alternative to SQL escape string.
     * 
     * @param   string $string
     * @return  string
     */

    public function escape(string $string)
    {
        return $this->conn->quote($string);
    }

    /**
     * Free database results.
     * 
     * @return void
     */

    public function freeResult()
    {
        $this->query->closeCursor();
    }

    /**
     * Destroy PDO connection.
     * 
     * @return void
     */

    public function close()
    {
        $this->conn = null;
        $this->connected = false;
    }

}