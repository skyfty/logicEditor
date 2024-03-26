<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

$id = $_POST['id'];
$pokeParts_id = $_POST['pokeParts_id'];
$parts_id = $_POST['parts_id'];
$type = $_POST['type'];

  if($type == 'get'){
    $sql = "SELECT * FROM matchingmodel WHERE pokeParts_id = $pokeParts_id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $images = [];  
      while ($row = $result->fetch_assoc()) {  
          $images[] = $row;  
      }  
      if (!empty($images)) {  
          echo json_encode(['message' => '200', 'data' => $images]);  
      } else {  
          echo json_encode(['message' => '数据为空']);  
      }
    }else{
      echo json_encode(['message' => '获取数据为空', ]);  
    }

  }else if($type == 'post'){
    $sql = "INSERT INTO matchingmodel (pokeParts_id, parts_id) VALUES ('$pokeParts_id', '$parts_id')";

    if ($conn->query($sql) === TRUE) {
      echo json_encode(['message' => '新增数据成功']); 
    }else{
      echo json_encode(['message' => '新增数据失败', ]);  
    }

  }else if($type == 'delete'){
    $sql = "DELETE FROM matchingmodel WHERE pokeParts_id = '$pokeParts_id' AND parts_id = '$parts_id'";  

    if ($conn->query($sql) === TRUE) {
      echo json_encode(['message' => '删除数据成功']); 
    }else{
      echo json_encode(['message' => '删除数据失败', ]);  
    }
  }

$conn->close();
?>