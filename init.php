<?php

$host = '813757-readme-12';
$login = 'root';
$password = 'root';
$database = 'readme';

$connect = mysqli_connect($host, $login, $password, $database);
mysqli_set_charset($connect, "utf8");
