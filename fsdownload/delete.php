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

// 获取要删除的记录的ID（假设从前端传递过来）
$id = $_POST['id'];
$children = $_POST['children'];
// 构建SQL删除语句
$sql = "DELETE FROM $children WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "记录删除成功";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 关闭数据库连接
$conn->close();
?>
