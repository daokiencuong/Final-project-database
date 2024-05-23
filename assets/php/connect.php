<?php
$host = "149.28.135.193";
$port = "5432";
$dbname = "Project_1";
$user = "postgres";
$password = "admin";

$connection = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$connection) {
    echo "Error to connect to database";
    exit(1);
}
?>
