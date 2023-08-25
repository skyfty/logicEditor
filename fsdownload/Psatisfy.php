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
  $content = $_POST['content'];
  $condition = $_POST['room_id'];
  $storypoint = $_POST['storypoint_id'];
  $role = $_POST['role'];
  $expression = $_POST['expression'];

    if($id !== null && $content !== null && $condition !== null && $role !== null && $expression !== null && $storypoint !== null){
      $sql = "UPDATE satisfy SET content='$content',room_id='$condition',storypoint_id='$storypoint',role='$role',expression='$expression'  WHERE id = $id";
      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo '修改成功'.$children.$storypoint;
      } else {
        echo "修改失败".$children;
      }
    }else{
      echo '缺少参数';
    }

  // 关闭数据库连接
  $conn->close();
?>
