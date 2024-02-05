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

try {
    // 创建数据库连接
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // 设置PDO错误模式为异常
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 定义要修改的表名和字段名
    $tableName = "deckGame";
    $fieldName = "data";
    $data = '{
        "tableauPiles": [
          {
              "cards":[
                  "6S",
                  "4S",
                  "8S",
                  "KH",
                  "4C",
                  "3C",
                  "9H"
              ]
          },
          {
              "cards":[
                  "7H",
                  "10H",
                  "5H",
                  "KC",
                  "5S",
                  "8H"
              ]
          },
          {
              "cards":[
                  "8C",
                  "AC",
                  "6H",
                  "3H"
              ]
          },
          {
              "cards":[
                  "4H",
                  "JS",
                  "7S"
              ]
          },
          {
              "cards":[
                  "QH",
                  "2H"
              ]
          },
          {
              "cards":[
                  "QS"
              ]
          }
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

    // 解码 JSON 数据为 PHP 数组
    $decodedData = json_decode($data, true);

    // 获取 tableauPiles 数组
    $tableauPiles = $decodedData['tableauPiles'];

    // 随机排序 tableauPiles 数组中每个元素的 cards 数组
    foreach ($tableauPiles as &$pile) {
        shuffle($pile['cards']);
    }

    // 将修改后的 tableauPiles 数据编码为 JSON
    $updatedData = json_encode($decodedData);

    // 查询并修改指定字段的数据
    $sql = "UPDATE $tableName SET $fieldName = '$updatedData'";
    $conn->exec($sql);

    echo "数据更新成功";
} catch(PDOException $e) {
    echo "连接失败: " . $e->getMessage();
}

// 关闭数据库连接
$conn = null;
?>