<?php
// getImages.php

$servername = "8.141.89.131";
$username = "root";
$password = "123456";
$dbname = "lvpenghui";

header("Access-Control-Allow-Origin: 3001");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM crop";
$result = $conn->query($sql);

$images = array();

if ($result->num_rows > 0) {
	$images = array();
    while ($row = $result->fetch_assoc()) {
          $images[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($images);

$conn->close();
?>
