<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 13-5-2016
 * Time: 13:45
 */
class ForbiddenWordQueryBuilder
{
    public function createForbiddenWord($word)
    {

        Database::query_safe("INSERT INTO `forbiddenwords`(`Word`) VALUES (?)",
            array(strtolower($word)));
    }

    // Get all forbiddenwords, get limited forbidden words, get a single word or searched words
    public function getForbiddenWords($limit = null, $word = null, $search = null, $wordsArray = null)
    {

        $query = "SELECT * FROM `forbiddenwords`";
        $suffix = " ORDER BY `Word` ASC";

        if ($limit != null) {

            $limit -= 1;
            $limit *= 10;
            $suffix .= " LIMIT " . $limit . ",10";
        }

        if ($word != null && $search != null) {

            $query .= " WHERE `Word`=? AND `Word` LIKE ?";

            $result = Database::query_safe($query . $suffix, array($word, "%" . $search . "%"));

            return $result;

        } else if ($word != null && Empty($search)) {

            $query .= " WHERE `Word`=?";

            $result = Database::query_safe($query . $suffix, array($word));

            return $result;

        } else if ($search != null && Empty($word)) {

            $query .= " WHERE `Word` LIKE ?";

            $result = Database::query_safe($query . $suffix, array("%" . $search . "%"));

            return $result;

        } else if ($wordsArray != null) {

            $where = false;
            $param = [];

            foreach ($wordsArray as $item) {
                if(!$where) {
                    $query .= " WHERE `Word` = ?";
                    $param[] = strtolower(trim($item));

                    $where = true;
                } else {
                    $query .= " OR `Word` = ?";
                    $param[] = strtolower(trim($item));
                }
            }

            return Database::query_safe($query, $param);
        } else {

            $result = Database::query($query . $suffix);

            return $result;
        }
    }

    public function updateForbiddenWord($oldWord, $newWord)
    {

        Database::query_safe("UPDATE `forbiddenwords` SET `Word`=? WHERE `Word`=?",
            array(strtolower($newWord), $oldWord));
    }

    public function deleteForbiddenWord($word)
    {

        Database::query_safe("DELETE FROM `forbiddenwords` WHERE `Word`=?",
            array($word));
    }
}