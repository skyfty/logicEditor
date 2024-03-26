<?php  
// 设置响应头允许跨域请求  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
require_once('../Api.php');  
  
// 从 POST 请求中获取 levelGroup_id 和 klondike_id  
$levelGroup_id = isset($_POST['levelGroup_id']) ? $_POST['levelGroup_id'] : null;  
$klondikeId = isset($_POST['klondike_id']) ? $_POST['klondike_id'] : null;  
  
// 验证必要的数据是否存在  
if ($levelGroup_id === null || $klondikeId === null) {  
    echo '错误：缺少必要的参数';  
    exit;  
}  
  
// 获取 levelGroupId 对应的最小 sequenceId  
$sequenceSql = "SELECT MIN(sequenceId) as minSequenceId FROM sequence_map WHERE levelGroupId = ?";  
$sequenceStmt = $conn->prepare($sequenceSql);  
$sequenceStmt->bind_param("i", $levelGroup_id);  
$sequenceStmt->execute();  
$sequenceResult = $sequenceStmt->get_result();  
$sequenceRow = $sequenceResult->fetch_assoc();  
$sequenceId = $sequenceRow['minSequenceId'];  
  
if ($sequenceId === null) {  
    // 如果找不到最小 sequenceId，则计算总数并加 1  
    $sequenceCountSql = "SELECT COUNT(*) as count FROM poke WHERE levelGroupId = ?";  
    $sequenceCountStmt = $conn->prepare($sequenceCountSql);  
    $sequenceCountStmt->bind_param("i", $levelGroup_id);  
    $sequenceCountStmt->execute();  
    $sequenceCountResult = $sequenceCountStmt->get_result();  
    $sequenceCount = $sequenceCountResult->fetch_assoc()['count'];  
    $sequenceId = $sequenceCount + 1;   
} else {  
    // 如果存在最小 sequenceId，则删除对应的记录  
    $deleteSql = "DELETE FROM sequence_map WHERE levelGroupId = ? AND sequenceId = ?";  
    $deleteStmt = $conn->prepare($deleteSql);  
    $deleteStmt->bind_param("ii", $levelGroup_id, $sequenceId);  
    $deleteResult = $deleteStmt->execute();  
  
    if (!$deleteResult) {  
        echo '删除错误';  
    } else {  
        echo '删除成功';  
    }  
}  
  
// 创建新的 poke 记录  
$name = '关卡' . $sequenceId;  
$list = $sequenceId - 1;  
  
$pokeSql = "INSERT INTO poke (levelGroupId, klondikeId, name, list, timeLimit, gold, experience, diamond, prop, stamina, sequenceId, zoom) 
                    VALUES ('$levelGroup_id', '$klondikeId', '$name', '$list', 60, 0, 0, 0, 0, 0, '$sequenceId', 1)";  
$pokeStmt = $conn->query($pokeSql);  
  
if ($pokeStmt) {  
    echo '创建成功';  
} else {  
    echo '创建失败' . $conn->error;  
}  
  
// 关闭数据库连接  
$conn->close();  
?> 