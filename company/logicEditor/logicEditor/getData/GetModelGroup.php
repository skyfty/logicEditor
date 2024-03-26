<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

$id = $_POST['id'];
$poke_id = $_POST['poke_id'];
$children = $_POST['children'];

	$images = array();

	if($id == ''){
    if($poke_id == ''){
      $sql = "SELECT * FROM $children";
    }else{
			switch($children){
				case 'modelgroup':
      		$sql = "SELECT id, picture, amount FROM $children WHERE poke_id = $poke_id";
					break;
			}
    }
	}else{
		$sql = "SELECT * FROM $children WHERE id = $id";
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