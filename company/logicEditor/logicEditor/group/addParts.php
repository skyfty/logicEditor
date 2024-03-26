<?php  
// 设置响应头允许跨域请求  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
require_once('../Api.php');  
  
// 从 POST 请求中获取 poke_id 和 poke_id  
$picture = isset($_POST['picture']) ? $_POST['picture'] : null;  
$poke_id = isset($_POST['poke_id']) ? $_POST['poke_id'] : null;  
  
// 验证必要的数据是否存在  
if ($picture === null || $poke_id === null) {  
    echo '错误：缺少必要的参数';  
    exit;  
}  
  
    // 如果找不到最小 sequenceId，则计算总数并加 1  
    $sequenceCountSql = "SELECT COUNT(*) as count FROM pokeparts WHERE poke_id = ?";  
    $sequenceCountStmt = $conn->prepare($sequenceCountSql);  
    $sequenceCountStmt->bind_param("i", $poke_id);  
    $sequenceCountStmt->execute();  
    $sequenceCountResult = $sequenceCountStmt->get_result();  
    $sequenceCount = $sequenceCountResult->fetch_assoc()['count'];  
    $sequenceId = $sequenceCount + 1;  

  
// 创建新的 poke 记录  
$name = '关卡' . $sequenceId; 
  
$pokeSql = "INSERT INTO pokeparts (picture, poke_id, name, chartlet, x, y, z, rotationX, rotationY, rotationZ, antle, type) 
                    VALUES ('$picture', '$poke_id', '$name', 'resource/parts/apple_01_cr.png', 0, 0, 0, 0, 0, 0, 1, '可拆解')";  
$pokeStmt = $conn->query($pokeSql);  
  
if ($pokeStmt) {   
    http_response_code(201); // 设置 HTTP 状态码为 201 Created  
    echo json_encode(['message' => '创建成功']);  
} else {  
    http_response_code(500); // 设置 HTTP 状态码为 500 Internal Server Error  
    echo json_encode(['error' => '创建失败', 'message' => $conn->error]);  
}  
  
// 关闭数据库连接  
$conn->close();  
?> 