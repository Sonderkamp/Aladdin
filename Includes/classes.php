<?php


function __autoload($class_name)
{

    //class directories
    $directories = array(
        ['Controller/', ".class.php"],
        ['Model/', ".php"],
        ['Model/QueryBuilder/', ".php"]
    );

    //for each directory
    foreach ($directories as $directory) {
        //see if the file exsists

        if (file_exists($directory[0] . $class_name . $directory[1])) {
            require($directory[0] . $class_name . $directory[1]);
            return;
        }
    }
}

spl_autoload_register(function ($name) {
    __autoload($name);
});







