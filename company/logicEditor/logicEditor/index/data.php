
<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');


require_once('../Api.php');

  $image = array();

	$sql = "SELECT * FROM levelgroup";
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

                                        $indexs = $rows['id'];
                                        $sqls1 = "SELECT * FROM pokeparts WHERE poke_id = '$indexs'";
                                        $results1 = $conn->query($sqls1);
                                        if ($results1->num_rows > 0) {
                                                while ($rows1 = $results1->fetch_assoc()) {
                                                    $rows['parts'][] = $rows1;
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