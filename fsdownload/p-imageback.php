<?php
  // 连接到数据库
  $servername = "8.141.89.131";
  $username = "root";
  $password = "123456";
  $dbname = "lvpenghui";

  header("Access-Control-Allow-Origin: http://localhost:8080");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  $conn = new mysqli($servername, $username, $password, $dbname);

  // 检查连接是否成功
  if ($conn->connect_error) {
      die("连接数据库失败: " . $conn->connect_error);
  }

  // 查询数据库并返回数据
  $room_id = $_POST['id'];
  $picture = $_POST['picture'];

  if ($room_id !== null && $picture !== null) {
    // 查询是否存在指定的 room_id
    $checkExistSql = "SELECT room_id FROM imageback WHERE room_id = $room_id";
    $existResult = $conn->query($checkExistSql);

    if ($existResult->num_rows > 0) {
      // 如果存在，更新对应的 picture
      $updateSql = "UPDATE imageback SET picture = '$picture' WHERE room_id = $room_id";
      $updateResult = $conn->query($updateSql);

      if ($updateResult === TRUE) {
        echo 'imageback 修改成功';
      } else {
        echo "imageback 修改失败: " . $conn->error;
      }
    } else {
      // 如果不存在，创建新的数据记录
      $insertSql = "INSERT INTO imageback (room_id, picture) VALUES ($room_id, '$picture')";
      $insertResult = $conn->query($insertSql);

      if ($insertResult === TRUE) {
        echo '新数据记录已创建';
      } else {
        echo "创建新数据记录失败: " . $conn->error;
      }
    }
  } else {
    echo '缺少参数';
  }

  // 关闭数据库连接
  $conn->close();
?>
