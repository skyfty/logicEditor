<?php  
// 设置响应头允许跨域请求  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
// 引入数据库连接文件  
require_once('../Api.php');  
  
// 检查是否接收到了POST请求中的id和children参数  
if (isset($_POST['id']) && isset($_POST['children'])) {  
    $id = $_POST['id'];  
    $children = $_POST['children'];  
  
    // 防止SQL注入，使用预处理语句  
    $stmt = $conn->prepare("DELETE FROM `$children` WHERE id = ?");  
    $stmt->bind_param("i", $id); // 假设id是整数类型  
  
    // 执行删除操作  
    if ($stmt->execute() === TRUE) {  
        echo json_encode(['message' => '记录删除成功']);  
    } else {  
        echo json_encode(['error' => "Error deleting record from $children with ID $id"]);  
    }  
  
    // 关闭预处理语句和数据库连接  
    $stmt->close();  
    $conn->close();  
} else {  
    // 如果没有接收到必要的参数，返回错误消息  
    http_response_code(400); // 设置HTTP响应状态码为400（Bad Request）  
    echo json_encode(['error' => '缺少必要的参数：id 和 children']);  
}  
?>