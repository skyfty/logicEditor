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
  $id = $_POST['id'];
  $x = $_POST['x'];
  $y = $_POST['y'];
  $room_id = $_POST['room_id'];
  $picture = $_POST['picture'];
  $z = $_POST['z'];
  $children = $_POST['children'];

    if($id !== null && $x !== null && $y !== null && $picture !== null && $room_id !== null && $z !== null){
      $sql = "UPDATE $children SET x = '$x', y = '$y', z = '$z', picture = '$picture',room_id='$room_id'  WHERE id = $id";
      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo 'buttonpoint修改成功'.$children;
      } else {
        echo "buttonpoint修改失败".$children;
      }
    }else{
      echo '缺少参数';
    }

  // 关闭数据库连接
  $conn->close();
?>
