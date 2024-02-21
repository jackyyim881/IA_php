<?php

$hostname = "localhost";
$username = "root";
$password = "";
$databasename = "SEHS4517IA";
$port = "3307";
$conn = new mysqli($hostname, $username, $password, $databasename, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>