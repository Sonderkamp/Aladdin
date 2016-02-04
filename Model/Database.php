<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 4-2-2016
 * Time: 11:39
 */
class Database
{
    private $conn, $result, $rows;

    public function __construct()
    {
        // connect to database
        $this->conn = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

        // ensure that PDO::prepare returns false when passed invalid SQL
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function __destruct()
    {
        // close connection as referenced by: http://php.net/manual/en/pdo.connections.php
        $this->conn = null;
    }

    public function query($sql)
    {
        try {
            $this->result = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            $this->rows = new ArrayObject($this->result);
            $this->result = $this->rows->getIterator();

            return true;

        } catch (PDOException $e) {
            printError($e);
        }
        return false;
    }

    public function query_safe($sql, $parameters)
    {
        try {
            $statement = $this->conn->prepare($sql);

            // execute SQL statement
            $result = $statement->execute($parameters);

            if ($result !== false) {
                $this->result = $statement->fetchAll(PDO::FETCH_ASSOC);

                $obj = new ArrayObject($this->result);
                $this->result = $obj->getIterator();

                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            printError($e);
        }
    }

    public function printError($e)
    {
        echo '<pre>';
        echo 'Regel: ' . $e->getLine() . '<br>';
        echo 'Bestand: ' . $e->getFile() . '<br>';
        echo 'Foutmelding: ' . $e->getMessage();
        echo '</pre>';
        exit(1);
    }

    public function getRow()
    {
        if ($this->result->valid()) {
            $tmp = $this->result->current();
            $this->result->next();
            return $tmp;

        }
        return null;
    }

    public function getRows()
    {
        return $this->rows;
    }
}