<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$login = 'root';
$password = '';
$database = 'readme';

$connect = mysqli_connect($host, $login, $password, $database);

mysqli_set_charset($connect, "utf8");

if (mysqli_connect_errno()) {
    echo "Ошибка установки соединения" . mysqli_connect_error();
    exit();
}
