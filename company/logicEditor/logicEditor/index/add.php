<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

// 使用内联视图计算记录数量并插入新数据
$insertSql = "INSERT INTO levelgroup (name)
              SELECT CONCAT('类型', COUNT(*)) 
              FROM (SELECT * FROM levelgroup) AS tmp";
$result = $conn->query($insertSql);

if ($result) {
    echo '增加成功';
} else {
    echo "增加失败";
}

$conn->close();
?>