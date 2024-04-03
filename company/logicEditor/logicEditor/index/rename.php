<?php  
// 设置响应头允许跨域请求  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
// 引入数据库连接文件  
require_once('../Api.php');  
  
// 检查是否接收到了必要的POST参数  
if (isset($_POST['id']) && isset($_POST['children'])) {  
    $id = $_POST['id'];  
    $children = $_POST['children'];  
      
    // 检查是否有第二个ID需要交换  
    if (isset($_POST['ids']) ) {  
        $ids = $_POST['ids'];  
        $name = $_POST['name'];  
          
        // 构建SQL查询语句以获取两个ID的name值  
        $selectSql = "SELECT name FROM `$children` WHERE id IN ('$id', '$ids')";  
        $result = $conn->query($selectSql);  
          
        // 检查是否确实有两个匹配的记录  
        if ($result && $result->num_rows === 2) {  
            $rows = $result->fetch_all(MYSQLI_ASSOC);  
            $name1 = $rows[0]['name'];  
            $name2 = $rows[1]['name'];  
              
            // 构建SQL更新语句以交换name值  
            // if ($id > $ids) {  
                $updateSql1 = "UPDATE `$children` SET name = ? WHERE id = ?";  
                $updateSql2 = "UPDATE `$children` SET name = ? WHERE id = ?";  
            // } else {  
            //     $updateSql1 = "UPDATE `$children` SET name = ? WHERE id = ?";  
            //     $updateSql2 = "UPDATE `$children` SET name = ? WHERE id = ?";  
            // }  
              
            // 准备并绑定参数以防止SQL注入  
            $stmt1 = $conn->prepare($updateSql1);  
            $stmt1->bind_param("ss", $name2, $id);  
            $stmt2 = $conn->prepare($updateSql2);  
            $stmt2->bind_param("ss", $name1, $ids);  
              
            // 执行更新操作  
            if ($stmt1->execute() && $stmt2->execute()) {  
                echo json_encode(['message' => '切换操作成功'.$name1.$name2]);  
            } else {  
                echo json_encode(['error' => '切换操作失败: ' . $conn->error]);  
            }  
              
            // 关闭预处理语句  
            $stmt1->close();  
            $stmt2->close();  
        } else {  
            echo json_encode(['error' => '未找到匹配的数据']);  
        }  
    } else {  
        // 如果没有第二个ID，则只更新单条记录的name值  
        $name = $_POST['name']; // 注意这里直接使用了$_POST['name']，确保这是安全的  
        $sql = "UPDATE `$children` SET name = ? WHERE id = ?";  
        $stmt = $conn->prepare($sql);  
        $stmt->bind_param("si", $name, $id); // 假设name是字符串，id是整数  
          
        if ($stmt->execute()) {  
            echo json_encode(['message' => '记录修改成功']);  
        } else {  
            echo json_encode(['error' => 'Error updating record: ' . $conn->error]);  
        }  
          
        // 关闭预处理语句  
        $stmt->close();  
    }  
} else {  
    // 如果缺少必要的参数，返回错误消息  
    http_response_code(400); // 设置HTTP响应状态码为400（Bad Request）  
    echo json_encode(['error' => '缺少必要的参数：id 和 children']);  
}  
  
// 关闭数据库连接  
$conn->close();  
?>