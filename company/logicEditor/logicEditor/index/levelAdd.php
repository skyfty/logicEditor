<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

// 前端传递的参数作为Klondike_id值
$klondikeId = $_POST['klondikeId'];
// $klondikeId = '3';
$level = $_POST['level'];

$jsonData = '{
  "tableau piles": [
      [
          "6S",
          "4S",
          "8S",
          "KH",
          "4C",
          "3C",
          "9H"
      ],
      [
          "7H",
          "10H",
          "5H",
          "KC",
          "5S",
          "8H"
      ],
      [
          "QC",
          "3S",
          "JH",
          "2C",
          "JC"
      ],
      [
          "8C",
          "AC",
          "6H",
          "3H"
      ],
      [
          "4H",
          "JS",
          "7S"
      ],
      [
          "QH",
          "2H"
      ],
      [
          "QS"
      ]
  ],
  "foundations": [
      "",
      "",
      "",
      ""
  ],
  "stock": [
      "JD",
      "10D",
      "AD",
      "4D",
      "10C",
      "8D",
      "6C",
      "5C",
      "KD",
      "AH",
      "9S",
      "QD",
      "7C",
      "5D",
      "7D",
      "3D",
      "KS",
      "2S",
      "2D",
      "AS",
      "9C",
      "6D",
      "9D",
      "10S"
  ],
  "waste": ""
}';

$variable = json_decode($jsonData, true);

// 生成1到100之间的随机两位数作为moves值
$moves = rand(1, 100);


// 查询满足klondikeId条件的记录数量
$listSql = "SELECT COUNT(*) AS total FROM poke WHERE Klondike_id = '$klondikeId'";
$listResult = $conn->query($listSql);
$row = $listResult->fetch_assoc();
$list = $row['total'];
$name = 'poke'.$row['total'];


// 将数据插入数据库表
$insertSql = "INSERT INTO poke (name,difficultyLevel,game, moves, Klondike_id, list) VALUES ('$name','$level','$jsonData', $moves, '$klondikeId', $list)";
$result = $conn->query($insertSql);

if ($result) {
    echo '增加成功';
} else {
    echo "增加失败".$klondikeId.$level.'456';
}

$conn->close();
?>