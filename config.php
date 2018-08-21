<?php
$dbType = "mysql";
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'test';

$connect = new PDO($dbType.':host='.$host.';dbname='.$db.';charset=utf8', $user, $password);