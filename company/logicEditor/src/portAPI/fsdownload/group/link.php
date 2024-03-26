<?php
// db_connect.php

$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>