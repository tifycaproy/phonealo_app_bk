<?php
global $db;

$db_hostname = "localhost";
$db_database = "call53clients";
$db_username = "uc53clie";
$db_password = "Agabama18";

/*
$db_hostname = "localhost";
$db_database = "amundocuba";
$db_username = "root";
$db_password = "alx";
*/


$am = new MeekroDB($db_hostname, $db_username, $db_password, $db_database);
$db = new MeekroDB($db_hostname, $db_username, $db_password, $db_database);

//mysql_query("SET CHARSET 'utf8'");
$db->query("SET CHARSET 'latin1'");

$db_hostname = "localhost";
$db_database = "call53clients";
$db_username = "uc53clie";
$db_password = "Agabama18";
$dbasterisk = new MeekroDB($db_hostname, $db_username, $db_password, $db_database);
