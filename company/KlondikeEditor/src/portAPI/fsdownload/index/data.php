
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

  $image = array();

	$sql = "SELECT * FROM levelGroup";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {
				$id = $row['id'];
				$sql1 = "SELECT * FROM klondike WHERE levelGroupId = $id ORDER BY sequenceId";
				$result1 = $conn->query($sql1);
				if ($result1->num_rows > 0) {
						while ($row1 = $result1->fetch_assoc()) {
                            $index = $row1['id'];
                            $sqls = "SELECT * FROM poke WHERE KlondikeId = '$index' ORDER BY list";
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
                                        $row1['levels'][] = $rows;
                                    }
                            }
                            $row['levelPack'][] = $row1;
						}
				}
				$image[] = $row;
			}
		}

echo json_encode($image);

$conn->close();
?>