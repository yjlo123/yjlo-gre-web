<?php
$conn_error = 'Could not connect!';

$config = parse_ini_file("config.ini");
$mysql_host = $config['dbhost'];
$mysql_user = $config['dbuser'];
$mysql_pass = $config['dbpass'];
$mysql_db = $config['dbname'];


$conn = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$conn->set_charset("utf8");
?>
