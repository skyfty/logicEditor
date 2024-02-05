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

 $levelGroupId = $_POST['levelGroupId'];
 $KlondikeId = $_POST['KlondikeId'];
 $id = $_POST['id'];
//$levelGroupId = 34;
//$KlondikeId = 161;
//$id = 675;

$image = array();

$sql = "SELECT * FROM levelGroup WHERE id = $levelGroupId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ids = $row['id'];
        $sql1 = "SELECT id,name,levelGroupId FROM klondike WHERE levelGroupId = $ids AND id = $KlondikeId";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $index = $row1['id'];
                $sqls = "SELECT id,name,levelGroupId,KlondikeId,difficultyLevel,deckGameId,gold,experience,diamond,prop,stamina FROM poke WHERE KlondikeId = '$index' AND id = $id";
                $results = $conn->query($sqls);
                if ($results->num_rows > 0) {
                    while ($rows = $results->fetch_assoc()) {
                        $indexs = $rows['deckGameId'];
                        $sqls1 = "SELECT * FROM deckGame WHERE id = '$indexs'";
                        $results1 = $conn->query($sqls1);
                        if ($results1->num_rows > 0) {
                            while ($rows1 = $results1->fetch_assoc()) {
                                $gameData = json_decode($rows1['data'], true);
                                $rows['game'] = $gameData;
                                $rows['moves'] = $rows1['moves'];
                            }
                        }
                        $row1['levels'] = $rows;
                    }
                }
                $row['levelPack'] = $row1;
            }
        }
        $image = $row;
    }
}

$response = array(
    'code' => 0, // 返回码参数
    'data' => $image  // 数据
);

if (!empty($image)) {
    http_response_code(200); // 成功的返回码
} else {
    http_response_code(404); // 未找到的返回码
    $response['code'] = 1;
    $response['data'] = null;
}

echo json_encode($response);

$conn->close();
?>