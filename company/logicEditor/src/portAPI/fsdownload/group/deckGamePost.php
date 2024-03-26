<?php
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');


$ids = $_POST['ids']; // 假设传入的ID参数为一个数组
// $ids = '[{"id":1,"difficultyLevel":"1","deckGame":"108","state":false,"difficultyScope":{"min":"1","max":"500"}},{"id":2,"difficultyLevel":"1","deckGame":"167","state":false,"difficultyScope":{"min":"1","max":"500"}}]';
$idsArray = json_decode($ids, true);
	$data = array();
foreach ($idsArray as $key => $id) {
    $sql = "SELECT id FROM poke WHERE deckGameId = '{$id['deckGame']}'";
    $result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while ($row = $result->fetch_assoc()) {

              $updateSql1 = "UPDATE deckGame SET isActive = 'false' WHERE id = '{$id['deckGame']}'";
              $conn->query($updateSql1);

              $sql = "SELECT * FROM deckGame WHERE moves >= '{$id['difficultyScope']['min']}' AND moves <= '{$id['difficultyScope']['max']}' AND isActive = 'false'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    $randomIndex = array_rand($rows);
                    $selectedRow = $rows[$randomIndex];
                    $selectedId = $selectedRow['id'];
                    
                    $updateSqls = "UPDATE poke SET deckGameId = '$selectedId', difficultyLevel='{$id['difficultyLevel']}', gold = '{$id['gold']}',experience = '{$id['experience']}',diamond = '{$id['diamond']}', prop = '{$id['prop']}', stamina = '{$id['stamina']}'  WHERE id = '{$row['id']}'";
                    $conn->query($updateSqls);
                    
                    $updateSql = "UPDATE deckGame SET isActive = 'true' WHERE id = $selectedId";
                    $conn->query($updateSql);
                    $data[$id['id']] = $selectedId;
                }

	    }
	}
}
echo json_encode($data);

$conn->close();
?>