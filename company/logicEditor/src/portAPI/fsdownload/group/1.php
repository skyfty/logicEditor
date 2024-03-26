<?php
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// getImages.php 
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type"); 
header('Content-Type: application/json'); 
//require_once './1.php'; 


$min = '1';
$max = '40';

$image = ''; 

$sql = "SELECT * FROM deckGame WHERE moves >= $min AND moves <= $max AND isActive = 'false'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $randomIndex = array_rand($rows);
    $selectedRow = $rows[$randomIndex];
    $selectedId = $selectedRow['id'];

    $image = $selectedId;
}

echo json_encode($image); 

$conn->close(); 

?>