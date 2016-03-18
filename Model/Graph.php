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

    public function getUsersMonth()
    {
        $list = Database::query("SELECT count(`Email`) AS amount, EXTRACT( YEAR_MONTH FROM `CreationDate` )  AS 'Date' FROM `user`
where `CreationDate` > CURRENT_DATE() - INTERVAL 12 MONTH
 GROUP BY YEAR(`CreationDate`), MONTH(`CreationDate`)");

        $csvString = 'amount,date' . "\n";
        $csvString .= '12,201602' . "\n";
        $csvString .= '10,201601' . "\n";
        $csvString .= '6,201512' . "\n";
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