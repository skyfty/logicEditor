<?php
// getImages.php
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

// 设置响应头允许跨域请求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 使用内联视图计算记录数量并插入新数据
$insertSql = "INSERT INTO levelGroup (name)
              SELECT CONCAT('类型', COUNT(*)) 
              FROM (SELECT * FROM levelGroup) AS tmp";
$result = $conn->query($insertSql);

if ($result) {
    echo '增加成功';
} else {
    echo "增加失败";
}

$conn->close();
?>