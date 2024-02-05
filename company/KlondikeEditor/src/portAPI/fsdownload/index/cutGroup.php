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

$id = $_POST['id'];
$children = 'poke';
$klondikeId = $_POST['klondikeId'];

// 查询 id 字段所在数据的 Klondike_id 字段和 list 字段的值
$selectSql = "SELECT KlondikeId, list FROM poke WHERE id = '$id'";
$result = $conn->query($selectSql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $oldKlondikeId = $row['Klondike_id'];
    $oldList = $row['list'];

    // 更新满足条件的数据
    $updateSql = "UPDATE poke SET list = list - 1 WHERE KlondikeId = '$oldKlondikeId' AND list > '$oldList'";
    if ($conn->query($updateSql) === TRUE) {
        // 将具有 $klondikeId 值的数据的 list 字段值加一，并将 id 所在的数据的 Klondike_id 改为 $klondikeId
        $updateSql = "UPDATE poke SET list = list + 1 WHERE KlondikeId = '$klondikeId'";
        if ($conn->query($updateSql) === TRUE) {
            $updateSql = "UPDATE poke SET KlondikeId = '$klondikeId',list = 0 WHERE id = '$id'";
            if ($conn->query($updateSql) === TRUE) {
                echo "修改操作成功";
            } else {
                echo "修改操作失败: " . $conn->error;
            }
        } else {
            echo "修改操作失败: " . $conn->error;
        }
    } else {
        echo "修改操作失败: " . $conn->error;
    }
} else {
    echo "未找到匹配的数据";
}

// 关闭数据库连接
$conn->close();
?>