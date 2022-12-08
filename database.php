<?php

function collegareDB(): mysqli
{
    $db = new mysqli($_ENV['DB_HOST'],$_ENV['DB_USER'],$_ENV['DB_PASS'],$_ENV['DB_BD']);

    if (!$db) {
        echo "Errore: Non è possibile collegarsi a MySQL.";
        echo "errno: " . mysqli_connect_errno();
        echo "error: " . mysqli_connect_error();
        exit;
    }
    $db->set_charset("utf8");
    return $db;
}

function debug($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    exit; 
}
