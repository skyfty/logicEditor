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

// 查询最大的 id 值
$maxIdSql = "SELECT MAX(id) as max_id FROM city";
$maxIdResult = $conn->query($maxIdSql);
if ($maxIdResult && $maxIdResult->num_rows > 0) {
    $row = $maxIdResult->fetch_assoc();
    $nextId = $row['max_id'] + 1;
} else {
    $nextId = 1;
}

$canvas_id = $_POST['canvas_id'];
$newCanvasValue = 'city00' . $nextId; // 构造新的 canvas 值
// 插入数据
$sqls = "INSERT INTO city (city,canvas_id) VALUES ('$newCanvasValue',$canvas_id )";
$results = $conn->query($sqls);

if ($results) {
    echo 'canvas修改成功';
} else {
    echo "canvas修改失败";
}

// 关闭数据库连接
$conn->close();
?>
