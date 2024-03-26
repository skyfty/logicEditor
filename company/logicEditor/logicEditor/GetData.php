<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('./Api.php');

$id = $_POST['id'];
$name = $_POST['name'];

	$images = array();

	if($id == ''){
		$sql = "SELECT * FROM $name";
	}else{
		$sql = "SELECT * FROM $name WHERE id = $id";
	}

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
	          $images[] = $row;
	    }
	}

echo json_encode($images);

$conn->close();
?>