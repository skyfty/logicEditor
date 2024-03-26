<?php  
// Set the response headers to allow cross-origin requests  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
// Include the necessary API file  
require_once('../Api.php');  
  
// Function to sanitize user input and prevent SQL injection  
function sanitizeInput($input) {  
    $input = trim($input);  
    $input = stripslashes($input);  
    $input = htmlspecialchars($input);  
    return $input;  
}  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    // Retrieve the data from the POST request  
    $picture = isset($_POST['picture']) ? sanitizeInput($_POST['picture']) : null;  
    $poke_id = isset($_POST['poke_id']) ? sanitizeInput($_POST['poke_id']) : null;  
    $amount = isset($_POST['amount']) ? sanitizeInput($_POST['amount']) : null;  
    $id = isset($_POST['id']) ? intval($_POST['id']) : null; // Assuming id is an integer  
  
    // Validate the necessary data  
    if (empty($picture) || empty($poke_id)) {  
        http_response_code(400);  
        echo json_encode(['error' => '错误：缺少必要的参数']);  
        exit;  
    }  
  
    // Check if the record already exists based on picture and poke_id  
    $checkSql = "SELECT id,amount FROM modelgroup WHERE picture = ? AND poke_id = ?";  
    $checkStmt = $conn->prepare($checkSql);  
    $checkStmt->bind_param("ss", $picture, $poke_id);  
    $checkStmt->execute();  
    $result = $checkStmt->get_result();    
    $row = $result->fetch_assoc(); 
    $existingId = $row['id'] ?? null;  
    $existingAmount = $row['amount'] ?? null; 
  
    // Prepare the SQL statement  
    if ($existingId !== null) {  
        // Update the existing record 
        $as = $amount + $existingAmount;
        $pokeSql = "UPDATE modelgroup SET picture = ?, poke_id = ?, amount = ? WHERE id = ?";  
        $pokeStmt = $conn->prepare($pokeSql);  
        $pokeStmt->bind_param("sssi", $picture, $poke_id, $as, $existingId);  
    } else {  
        // Insert a new record  
        $pokeSql = "INSERT INTO modelgroup (picture, poke_id, amount) VALUES (?, ?, ?)";  
        $pokeStmt = $conn->prepare($pokeSql);  
        $pokeStmt->bind_param("sss", $picture, $poke_id, $amount);  
    }  
  
    // Execute the statement  
    if ($pokeStmt->execute()) {  
        http_response_code(200);  
        echo json_encode(['success' => '操作成功'.$as]);  
    } else {  
        http_response_code(500);  
        echo json_encode(['error' => '错误：数据库操作失败']);  
    }  
  
    // Close the statement  
    $pokeStmt->close();  
    $checkStmt->close();  
}