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
$id = $_POST['id'];
$age = $_POST['age'];
$name = $_POST['name'];
$property = $_POST['property'];
$room_id = $_POST['room_id'];
$children = $_POST['children'];

    $names =  "INSERT INTO plot (name,property) VALUES ('$name','$property')";
    $names1 = "INSERT INTO storypoint (name,room_id) VALUES ('$id.$name.$age','$room_id')";
      // 查询数据库并返回数据
      $sqls = $children == 'plot' ? $names : $names1;
      $results = $conn->query($sqls);
      if ($results) {
        echo 'storypoint增加成功';
      } else {
        echo "storypoint增加失败";
      }

// 关闭数据库连接
$conn->close();
?>