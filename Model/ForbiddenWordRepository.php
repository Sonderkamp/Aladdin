<?php

/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 25-4-2016
 * Time: 11:55
 */
class ForbiddenWordRepository
{
    public function createForbiddenWord($word) {

        Database::query_safe("INSERT INTO `forbiddenwords`(`Word`) VALUES (?)",
            array(strtolower($word)));
    }

    public function getForbiddenWords($limit = null, $word = null, $search = null) {

        $query = "SELECT * FROM `forbiddenwords`";

        if($word != null && $search != null) {

            $query .= " WHERE `Word`=? AND `Word` LIKE ?";
        } else if($word != null && Empty($search)) {

            $query .= " WHERE `Word`=?";
        } else if($search != null && Empty($word)) {

            $query .= " WHERE `Word` LIKE ?";
        }

        $query .= " ORDER BY `Word` ASC";

        if($limit != null) {

            $limit -= 1;
            $limit *= 10;
            $query .= " LIMIT ".$limit.",10";
        }

        if($word != null && $search != null) {

            $result = Database::query_safe($query, array($word,"%".$search."%"));

            return $this->createReturnArray($result);
        } else if($word != null && Empty($search)) {

            $result = Database::query_safe($query, array($word));

            return $this->createReturnArray($result, true);
        } else if($search != null && Empty($word)) {

            $result = Database::query_safe($query, array("%".$search."%"));

            return $this->createReturnArray($result);
        } else {

            $result = Database::query($query);

            return $this->createReturnArray($result);
        }
    }

    public function updateForbiddenWord($old_word, $new_word) {

        Database::query_safe("UPDATE `forbiddenwords` SET `Word`=? WHERE `Word`=?",
            array(strtolower($new_word), $old_word));
    }

    public function deleteForbiddenWord($word) {

        Database::query_safe("DELETE FROM `forbiddenwords` WHERE `Word`=?",
            array($word));
    }

    // Dit is alleen tellen voor de pagination
    public function countForbiddenWords($search = null) {

        $query = "SELECT COUNT(*) AS `number` FROM `forbiddenwords`";

        if($search != null) {

            $query .= " WHERE `Word` LIKE ?";
            $result = Database::query_safe($query,array("%".$search."%"));
        } else {

            $result = Database::query($query);
        }

        return $result[0]["number"];
    }

    // If first is set only the first word in the array will be returned
    public function createReturnArray($result, $first = null) {

        if(!Empty($result)) {

            if($first != null) {
                return $result[0]["Word"];
            }

            $returnArray = array();

            for ($i = 0; $i < count($result); $i++) {

                $returnArray[$i] = $result[$i]["Word"];
            }

            return $returnArray;
        } else {
            return null;
        }
    }

    // True als hij niet fout is, False als het woord niet goedgekeurt is
    public function isValid($word) {

        return Empty($this->getForbiddenWords(null,strtolower($word)));
    }
}