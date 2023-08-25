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
$x = $_POST['x'];
$y = $_POST['y'];
$z = $_POST['z'];
$room_id = $_POST['room_id'];
$picture = $_POST['picture'];
  if($room_id == null && $picture == null && $x == null && $y == null && $z == null){
      echo '参数为空'.$room_id, $x, $y,$z, $picture;
  }else{
      // 查询数据库并返回数据
      $sqls="INSERT INTO buttonpoint (room_id,x,y,z, picture) VALUES ($room_id, $x, '$y', '$z', '$picture')";
      $results = $conn->query($sqls);
      if ($results) {
        echo 'buttonpoint增加成功';
      } else {
        echo "buttonpoint增加失败".$room_id, $x, $y,$z, $picture;
      }
  }

// 关闭数据库连接
$conn->close();
?>