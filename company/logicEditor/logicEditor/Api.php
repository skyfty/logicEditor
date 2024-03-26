<?php
// db_connect.php

$username = "root";

$servername = "localhost";
$password = "lvpenghui";

// $servername = "8.141.89.131";
// $password = "hui.1234";

$dbname = "logicEditor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>