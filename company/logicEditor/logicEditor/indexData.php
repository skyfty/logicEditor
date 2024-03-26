<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('./Api.php');

	$sql = "SELECT * FROM klondike";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$images = array();
	    while ($row = $result->fetch_assoc()) {
            $index = $row['id'];

            $sqls = "SELECT id, name, game, moves, level, list FROM poke WHERE Klondike_id = '$index' ORDER BY list";
            $results = $conn->query($sqls);
            if ($results->num_rows > 0) {
              $image = array();
                while ($rows = $results->fetch_assoc()) {
                      // 解析game字段的JSON数据
                      $gameData = json_decode($rows['game'], true);
                      $rows['game'] = $gameData;
                      $image[] = $rows;
                }
                $row['levels'] = $image;
            }
            
	          $images[] = $row;
	    }
	}


echo json_encode($images);

$conn->close();
?>