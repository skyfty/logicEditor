<?php
// 连接到数据库
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

  // 设置响应头允许跨域请求
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli($servername, $username, $password, $dbname);
// 检查连接是否成功
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

// 获取要删除的记录的ID（假设从前端传递过来）
$id = $_POST['id'];
$name = $_POST['name'];
// 构建SQL删除语句
$sql = "DELETE FROM $name WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "记录删除成功";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 关闭数据库连接
$conn->close();
?>
