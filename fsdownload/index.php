<?php
// 连接到数据库
$servername = "8.141.89.131";
$username = "root";
$password = "123456";
$dbname = "lvpenghui";

header("Access-Control-Allow-Origin: http://8.141.89.131/");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli($servername, $username, $password, $dbname);
// 检查连接是否成功
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}
  // 查询数据库并返回数据
  $result = $conn->query("SELECT * FROM canvas");
  if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
      $result1 = $conn->query("SELECT * FROM city ");
      if ($result1->num_rows > 0) {
        $data1 = array();
        while ($row1 = $result1->fetch_assoc()) {
            if($row['id'] == $row1['canvas_id']){
              $result2 = $conn->query("SELECT * FROM room ");
              if ($result2->num_rows > 0) {
                $data2 = array();
                while ($row2 = $result2->fetch_assoc()) {
                    if($row1['canvas_id'] == $row2['canvas_id'] && $row1['id'] == $row2['city_id']) {
                      $result3 = $conn->query("SELECT * FROM children ");
                      if ($result3->num_rows > 0) {
                        $data3 = array();
                        while ($row3 = $result3->fetch_assoc()) {
                            $result4 = $conn->query("SELECT * FROM {$row3['children']}");
                            if ($result4->num_rows > 0) {
                              $data4 = array();
                              while ($row4 = $result4->fetch_assoc()) {
                                  if($row2['id'] == $row4['room_id']) {
                                    $data4[] = $row4;
                                    $row3[$row3['children']] = $data4;
                                  }
                              }
                            
                          }
                          $data3[] = $row3;
                          $row2['children'] = $data3;
                        }
                      }
                      
                      $data2[] = $row2;
                      $row1['room'] = $data2;
                    }
                }
              }
              $data1[] = $row1;
            }
        }
      }
      $row['city'] = $data1;
      $data[] = $row;
    }
    echo json_encode($data);
  } else {
    echo "没有找到数据";
  }

// 关闭数据库连接
$conn->close();
?>