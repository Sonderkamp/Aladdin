<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 17-3-2016
 * Time: 20:44
 */
class Graph
{
    public function getWishes()
    {
        $list = Database::query("SELECT count(*) AS amount, CAST(Date AS DATE) AS 'Date' FROM `wish`
GROUP BY CAST(Date AS DATE)");


        $csvString = 'amount,date' . "\n";
        foreach ($list as $fields) {
            $csvString .= $this->tocsv($fields) . "\n";
        }

        echo $csvString;
    }

    public function getUsers()
    {
        $list = Database::query(" SELECT count(`Email`) AS amount, CAST(`CreationDate` AS DATE) AS 'Date' FROM `user`
GROUP BY CAST(`CreationDate` AS DATE)");

        $csvString = 'amount,date' . "\n";
        foreach ($list as $fields) {
            $csvString .= $this->tocsv($fields) . "\n";
        }

        echo $csvString;
    }

    // source:http://stackoverflow.com/questions/16352591/convert-php-array-to-csv-string
    private function tocsv($input)
    {
        $delimiter = ',';
        $enclosure = '"';

        // Open a memory "file" for read/write...
        $fp = fopen('php://temp', 'r+');
        // ... write the $input array to the "file" using fputcsv()...
        fputcsv($fp, $input, $delimiter, $enclosure);
        // ... rewind the "file" so we can read what we just wrote...
        rewind($fp);
        // ... read the entire line into a variable...
        $data = fread($fp, 1048576);
        // ... close the "file"...
        fclose($fp);
        // ... and return the $data to the caller, with the trailing newline from fgets() removed.
        return rtrim($data, "\n");

    }
}