<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 09/03/2016
 * Time: 14:30
 */
class Quote
{
    public $source, $content;

    public function getQuote()
    {
        //TODO read quote shown and eliminate these from pool
        $result = Database::query("SELECT * FROM quote");

        $random = rand(0 , count($result) -1);

        $this->content = $result[$random]["QuoteContent"];
        $this->source = $result[$random]["Source"];

        return $this;
    }
}