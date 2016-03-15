<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 4-2-2016
 * Time: 11:39
 */
class Database
{

    private static function open()
    {
        // connect to database
        $var = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

        // ensure that PDO::prepare returns false when passed invalid SQL
        $var->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $var->query("SET NAMES 'utf8'");

        return $var;
    }


    public static function query($sql)
    {
        try {
            $conn = Database::open();
            $result = $conn->query($sql);

            if ($result === false) {
                return false;
            }

            return $result->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            Database::printError($e);
        }
        return false;
    }

    public static function query_safe($sql, $parameters)
    {
        try {
            $conn = Database::open();

            $statement = $conn->prepare($sql);

            if ($statement === false)
                return false;
            // execute SQL statement
            $result = $statement->execute($parameters);

            if ($result !== false) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $result;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            Database::printError($e);
        }
    }

    public static function printError($e)
    {
        echo '<pre>';
        echo 'Regel: ' . $e->getLine() . '<br>';
        echo 'Bestand: ' . $e->getFile() . '<br>';
        echo 'Foutmelding: ' . $e->getMessage();
        echo '</pre>';
        exit(1);
    }

    public static function getPDO()
    {
        return Database::open();
    }
    public static function transaction_action_safe($pdo, $sql, $parameters)
    {
        try {

            $statement = $pdo->prepare($sql);

            if ($statement === false)
                return false;
            // execute SQL statement
            $result = $statement->execute($parameters);

            if ($result !== false) {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                return $result;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            Database::printError($e);
        }
    }
}