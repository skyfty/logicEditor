<?php  
// 设置响应头允许跨域请求  
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Headers: Content-Type");  
header('Content-Type: application/json');  
  
require_once('./Api.php');  
 
// 查询数据库并返回数据  
$id = $_POST['id'];  
$children = $_POST['children'];  
$data = $_POST['data'];  
  
// 解析 JSON 字符串为 PHP 数组  
$dataArray = json_decode($data, true);  
  
// 检查 JSON 是否成功解码  
if (!$dataArray) {  
    echo json_encode(['error' => '无法解析 JSON 数据']);  
    exit;  
}  
   
// 使用 switch-case 进行条件判断  
switch ($children) {  
    case 'pokeparts':  
        // 使用预处理语句来避免 SQL 注入  
        $stmt = $conn->prepare("UPDATE pokeparts SET   
            picture=?, x=?, y=?, z=?, rotationX=?, rotationY=?, rotationZ=?, scaleX=?, scaleY=?, scaleZ=?, antle=?, type=? 
            WHERE id = ?");  
          
        // 绑定参数  
        $stmt->bind_param("sdddddddddssi",  
            $dataArray[0]['picture'],  
            $dataArray[1]['x'],  
            $dataArray[1]['y'],  
            $dataArray[1]['z'],  
            $dataArray[2]['rotationX'],  
            $dataArray[2]['rotationY'],  
            $dataArray[2]['rotationZ'], 
            $dataArray[3]['scaleX'],  
            $dataArray[3]['scaleY'],  
            $dataArray[3]['scaleZ'], 
            $dataArray[4]['antle'],  
            $dataArray[4]['type'],  
            $id  
        );  
          
        // 执行预处理语句  
        if ($stmt->execute()) {  
            echo json_encode(['message' => '信息已同步']);  
        } else {  
            echo json_encode(['error' => '信息同步失败', 'error_message' => $conn->error]);  
        }  
          
        // 关闭预处理语句  
        $stmt->close();  
    break;  

    case 'editPokeparts':  
        // 使用预处理语句来避免 SQL 注入  
        $stmt = $conn->prepare("UPDATE pokeparts SET   
            x=?, y=?, z=?, rotationX=?, rotationY=?, rotationZ=?, scaleX=?, scaleY=?, scaleZ=? 
            WHERE id = ?");  
          
              // 转换角度为弧度（如果需要的话）  
    $rotationXInRadians = rad2deg($dataArray[1]['_x']);
    $rotationYInRadians = rad2deg($dataArray[1]['_y']);
    $rotationZInRadians = rad2deg($dataArray[1]['_z']);
        // 绑定参数  
        $stmt->bind_param("dddddddddi",  
            $dataArray[0]['x'],  
            $dataArray[0]['y'],  
            $dataArray[0]['z'],
            $rotationXInRadians, 
            $rotationYInRadians, 
            $rotationZInRadians, 
            $dataArray[2]['x'],  
            $dataArray[2]['y'],
            $dataArray[2]['z'],    
            $id  
        );  
          
        // 执行预处理语句  
        if ($stmt->execute()) {  
            echo json_encode(['message' => '信息已同步']);  
        } else {  
            echo json_encode(['error' => '信息同步失败', 'error_message' => $conn->error]);  
        }  
          
        // 关闭预处理语句  
        $stmt->close();  
    break;

    case 'poke':  
        // 使用预处理语句来避免 SQL 注入  
        $stmt = $conn->prepare("UPDATE poke SET   
            timeLimit=?, gold=?, experience=?, diamond=?, prop=?, stamina=?  
            WHERE id = ?");  
            
        // 绑定参数  
        $stmt->bind_param("ssssssi",  
            $dataArray[0]['timeLimit'], 
            $dataArray[0]['gold'],  
            $dataArray[0]['experience'],  
            $dataArray[1]['diamond'],  
            $dataArray[1]['prop'],  
            $dataArray[1]['stamina'],  
            $id  
        );  
            
        // 执行预处理语句  
        if ($stmt->execute()) {  
            echo json_encode(['message' => '信息已同步']);  
        } else {  
            echo json_encode(['error' => '信息同步失败', 'error_message' => $conn->error]);  
        }  
            
        // 关闭预处理语句  
        $stmt->close();  
    break; 
    
    case 'pokesArguments':  
        // 使用预处理语句来避免 SQL 注入  
        $stmt = $conn->prepare("UPDATE poke SET     
            PositionX=?, PositionY=?, PositionZ=?,  
            RotationX=?, RotationY=?, RotationZ=?,  
            ScaleX=?, ScaleY=?, ScaleZ=?, Projection=?,  
            Size=?, FieldOfView=?, zoom=?, Near=?, Far=?  
            WHERE id = ?"); 
            
        // 绑定参数  
        $stmt->bind_param("dddddddddssssssi",  
            $dataArray[0][0]['x'],  
            $dataArray[0][0]['y'],  
            $dataArray[0][0]['z'],  
            $dataArray[0][1]['x'],  
            $dataArray[0][1]['y'],  
            $dataArray[0][1]['z'],  
            $dataArray[0][2]['x'],  
            $dataArray[0][2]['y'],  
            $dataArray[0][2]['z'],  
            $dataArray[1][0]['Projection'],  
            $dataArray[1][1]['Size'],  
            $dataArray[1][2]['FieldOfView'],  
            $dataArray[1][3]['Zoom'], // 假设zoom是整数  
            $dataArray[1][4]['Near'],  
            $dataArray[1][4]['Far'],  
            $id // 假设id也是整数  
        );
            
        // 执行预处理语句  
        if ($stmt->execute()) {  
            echo json_encode(['message' => '信息已同步', 'data' => $dataArray]);  
        } else {  
            echo json_encode(['error' => '信息同步失败']);  
        }  
            
        // 关闭预处理语句  
        $stmt->close();  
    break; 
      
    default:  
        echo json_encode(['error' => '无效的 children 值']);  
        break;  
}  
  
// 关闭数据库连接  
$conn->close();  
?>