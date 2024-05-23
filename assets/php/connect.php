<?php
$host = "45.32.107.40";
$port = "5432";
$dbname = "Project";
$user = "postgres";
$password = "admin";

$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$connection) {
    echo "Error to connect to database";
    exit(1);
}
?>
