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

$ids = $_POST['ids'];

$data = array();

$idsArray = json_decode($ids, true);
foreach ($idsArray as $id) {
    $sql = "SELECT * FROM deckGame WHERE moves >= '{$id['difficultyScope']['min']}' AND moves <= '{$id['difficultyScope']['max']}' AND isActive = 'false'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $randomIndex = array_rand($rows);
        $selectedRow = $rows[$randomIndex];
        $selectedId = $selectedRow['id'];
    
        // 更新isActive字段为true
        $updateSql = "UPDATE deckGame SET isActive = 'true' WHERE id = $selectedId";
        $conn->query($updateSql);
    
        // $image = $selectedId;
        $data[] = $selectedId;
        // echo $selectedId;
    // }else{
    //     echo '牌库已无该难度牌局'.$id['difficultyScope']['min'];
    }
}
echo json_encode($data);


// echo json_encode($image); 

$conn->close(); 

?>