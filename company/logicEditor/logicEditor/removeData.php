<?php    
// 设置响应头以允许跨域请求    
header("Access-Control-Allow-Origin: *");    
header("Access-Control-Allow-Headers: Content-Type");    
header('Content-Type: application/json');    
    
// 引入数据库连接类或其他API类（假设它存在）    
require_once('./Api.php');     
  
// 初始化响应变量  
$response = [];  
  
// 获取要删除的记录的ID和表名（假设从前端传递过来）    
$id = isset($_POST['id']) ? $_POST['id'] : null;    
$children = isset($_POST['children']) ? $_POST['children'] : null;    
  
// 确保数据库连接已建立  
if (!$conn) {  
    die("数据库连接失败");  
}  
  
// 对输入进行验证和清理，防止SQL注入    
$id = $conn->real_escape_string($id);    
$children = $conn->real_escape_string($children);    
  
// 定义一个函数来处理删除操作并返回响应    
function deleteRecord($conn, $table, $id) {    
    $deleteSql = "DELETE FROM `$table` WHERE id = '$id'";    
    if ($conn->query($deleteSql) === TRUE) {   
        return [    
            'message' => '记录删除成功',    
            'code' => 1  
        ];   
    } else {  
        return [    
            'message' => '记录删除失败',    
            'code' => 0  
        ];   
    }    
}    
    
// 处理不同表名的删除请求    
if ($children === 'parts') {    
    // 查询文件名    
    $selectSql = "SELECT filename FROM `$children` WHERE id = '$id'";    
    $result = $conn->query($selectSql);    
    if ($result && $result->num_rows > 0) {    
        $row = $result->fetch_assoc();    
        $fileName = $row['filename'];    
    
        // 删除文件    
        $filePath = 'resource/parts/' . $fileName;    
        if (file_exists($filePath)) {    
            unlink($filePath);    
            // 删除数据库记录并获取响应  
            $response = deleteRecord($conn, $children, $id);  
        } else {  
            $response = [    
                'message' => '文件不存在',    
                'code' => 0  
            ];    
        }    
    } else {   
        $response = [    
            'message' => '数据记录不存在',    
            'code' => 0  
        ];    
    }    
} elseif ($children === 'pokeparts' || $children === 'modelgroup') {    
    // 直接删除数据库记录并获取响应  
    $response = deleteRecord($conn, $children, $id);  
} else {    
    // 如果表名不是预期的，则输出错误信息并终止脚本    
    die('数据表不存在或无权访问');    
}    
// 返回 JSON 响应    
echo json_encode($response);   
    
// 关闭数据库连接    
$conn->close();    
?>