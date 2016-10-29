<?php

$conn_error = 'Could not connect!';

$mysql_host = 'localhost';
$mysql_user = 'yjlo123';
$mysql_pass = 'yjlo+19920829';
$mysql_db = 'yjlogre';


$conn = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$conn->set_charset("utf8");
?>
