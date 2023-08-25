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

$content = $_POST['content'];
$room = $_POST['room_id'];
$condition = $_POST['storypoint_id'];
$role = $_POST['role'];
$expression = $_POST['expression'];
  if($content == null && $condition == null && $role == null && $expression == null && $room !== null){
      echo '参数为空'.$room_id, $x, $y,$z, $picture;
  }else{
      // 查询数据库并返回数据
      $sqls="INSERT INTO satisfy (content,storypoint_id,role,expression,room_id) VALUES ('$content','$condition','$role','$expression','$room')";
      $results = $conn->query($sqls);
      if ($results) {
        echo '增加成功';
      } else {
        echo "增加失败";
      }
  }

// 关闭数据库连接
$conn->close();
?>