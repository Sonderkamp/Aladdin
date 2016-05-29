<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 29/05/2016
 * Time: 22:46
 */
class QueryBuilder
{
    /**
     * @param $query
     * @param null $params
     * @return array|bool
     *
     * if params is not empty will execute safe query. Otherwise regular query
     */
    protected function executeQuery($query, array $params)
    {
        if (!empty($params)) {
            return Database::query_safe($query, $params);
        } else {
            return Database::query($query);
        }
    }
}