<?php

function htmlspecialcharsWithNL($string)
{
    return nl2br(htmlspecialchars($string));
}


?>
