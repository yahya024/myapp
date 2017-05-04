<?php

define('SERVERNAME', 'localhost');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');
define('DB_NAME', 'myapp');
$option = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

try {
    $con = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DB_NAME,
        MYSQL_USERNAME,
        MYSQL_PASSWORD,
        $option);
    
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $ex) {
    echo "The connection failed";
}
