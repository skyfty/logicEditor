<?php
// getImages.php

$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

// 设置响应头允许跨域请求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 $id = $_POST['id'];
	$sql = "SELECT * FROM levelDifficulty WHERE id = '$id'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$images = array();
	    while ($row = $result->fetch_assoc()) {
	          $images[] = $row;
	    }
	}


echo json_encode($images);

$conn->close();
?>