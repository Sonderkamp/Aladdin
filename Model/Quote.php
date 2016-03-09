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

    public function __construct()
    {


    }

    public function getQuote(){
        //TODO read quote shown and eliminate these from pool

        $result = Database::query_safe("
            SELECT * FROM quote
            JOIN quoteShown ON quote.Id = quoteShown.Quote
            "
        );

        $random = rand(0 , count($this->result));

        $this->content = $result[$random]["Content"];
        $this->source = $result[$random]["Source"];

        return $this->content . $this->source;
    }
}