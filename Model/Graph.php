<?php

/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 17-3-2016
 * Time: 20:44
 */
class Graph
{
//    public function getWishes()
//    {
//        $list = Database::query("SELECT count(*) AS amount, CAST(Date AS DATE) AS 'Date' FROM `wish`
//GROUP BY CAST(Date AS DATE)");
//
//
//        $csvString = 'amount,date' . "\n";
//        foreach ($list as $fields) {
//            $csvString .= $this->tocsv($fields) . "\n";
//        }
//
//        echo $csvString;
//    }

    public function cats()
    {
        $list = Database::query("
        SELECT count(*) AS count,
          CASE WHEN (TIMESTAMPDIFF(YEAR,`Dob`,CURDATE()) < 18)
            THEN \"Kind (onder 18)\"
          WHEN TIMESTAMPDIFF(YEAR,`Dob`,CURDATE()) BETWEEN 18 AND 70
            THEN \"Volwassen\"
            ELSE \"Ouderen\"
          END AS category
        FROM `user`
        GROUP BY category");

        $csvString = 'amount,cat' . "\n";

        foreach ($list as $row) {
            $csvString .= $row["count"] . ',' . $row["category"] . "\n";
        }

        $list = Database::query("
        SELECT count(*) AS handicap FROM `user`
        WHERE `Handicap` = TRUE;");
        foreach ($list as $row) {
            $csvString .= $row["handicap"] . ',' . "Handicap" . "\n";
        }

        echo $csvString;
    }

    public function monthly($month = null)
    {
        if ($month === null) {
            $month = $_GET["month"];
        }
        $list = Database::query_safe("

        SELECT count(*) AS count,
          CASE WHEN (TIMESTAMPDIFF(YEAR,`Dob`,CURDATE()) < 18)
            THEN \"Kind (onder 18)\"
          WHEN TIMESTAMPDIFF(YEAR,`Dob`,CURDATE()) BETWEEN 18 AND 70
            THEN \"Volwassen\"
            ELSE \"Ouderen\"
          END AS category
        FROM `user`
        where EXTRACT( YEAR_MONTH FROM `CreationDate` ) = ?
        GROUP BY category", array($month));

        $csvString = 'value,name' . "\n";

        foreach ($list as $row) {
            $csvString .= $row["count"] . ',' . $row["category"] . "\n";
        }

        $list = Database::query_safe("
        SELECT count(*) AS handicap FROM `user`
        WHERE `Handicap` = TRUE AND EXTRACT( YEAR_MONTH FROM `CreationDate` ) = ?", array($month));
        foreach ($list as $row) {
            $csvString .= $row["handicap"] . ',' . "Handicap" . "\n";
        }

        echo $csvString;
    }

    public function age()
    {
        $list = Database::query("SELECT count(*) as count , TIMESTAMPDIFF(YEAR,`Dob`,CURDATE()) as age from `user` group by age");

        $csvString = 'amount,cat' . "\n";

        foreach ($list as $row) {
            $csvString .= $row["count"] . ',' . $row["age"] . "\n";
        }


        echo $csvString;
    }

    public function usersMonth()
    {
        $list = Database::query("
        SELECT count(`Email`) AS amount, EXTRACT( YEAR_MONTH FROM `CreationDate` )  AS 'Date' FROM `user`
        WHERE `CreationDate` > CURRENT_DATE() - INTERVAL 12 MONTH
        GROUP BY YEAR(`CreationDate`), MONTH(`CreationDate`)");

        $csvString = 'amount,date' . "\n";
        foreach ($list as $fields) {
            $csvString .= $this->tocsv($fields) . "\n";
        }

        echo $csvString;
    }


    public function matches()
    {
        $list = Database::query("select u1.Lat as 'from_lat', u1.Lon as 'from_lon', u2.Lat as 'to_lat', u2.Lon as 'to_lon' from matches as m
join wish as w on m.wish_id = w.Id
join User as u1 on w.User = u1.Email
join User as u2 on m.user_Email = u2.Email
where m.`IsActive` = 1 AND m.`IsSelected` = 1;");

        $csvString = 'from_lat,from_lon,to_lat,to_lon' . "\n";
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