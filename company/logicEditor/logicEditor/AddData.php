<?php  
  
  // 设置响应头以允许跨域请求  
  header("Access-Control-Allow-Origin: *");  
  header("Access-Control-Allow-Headers: Content-Type");  
  header('Content-Type: application/json');  
    
  // 引入 API 类（假设它存在）  
  require_once('./Api.php');  
    
  // 获取上传的文件和额外参数  
  $file = $_FILES['file'];  
  $name = $_POST['name'];

// 获取文件名（不带后缀）  
$a = pathinfo($file['name'], PATHINFO_FILENAME);  

// 检查数据库中是否存在相同的名称  
$checkQuery = "SELECT * FROM `$name` WHERE `name` = '$a'";  
$checkResult = $conn->query($checkQuery);  

if ($checkResult->num_rows > 0) {  
    // 数据库中已存在相同名称，上传失败  
    $response = [  
        'message' => '名字已存在，无法上传资源'  
    ];  
} else {  
    
  // 指定保存文件的目录  
  $uploadDir = 'resource/' . $name . '/';  
    
  // 确保目录存在  
  if (!is_dir($uploadDir)) {  
      mkdir($uploadDir, 0777, true);  
  }  
    
    // 生成唯一文件名（或使用原始文件名，这里视需求而定）  
    $uniqueFileName = $file['name'];  
      
    // 将文件移动到指定目录  
    $targetPath = $uploadDir . $uniqueFileName;  
    
  if (move_uploaded_file($file['tmp_name'], $targetPath)) {  
      // 文件移动成功  
    // 执行插入操作  
    $insertQuery = "INSERT INTO `$name` (`name`, `filename`, `filepath`) VALUES ('$a', '$uniqueFileName', '$targetPath')";  
    if ($conn->query($insertQuery) === TRUE) {  
        // 插入成功  
        $response = [  
            'message' => '文件上传成功并已存入数据库',  
            'name' => $name,  
            'fbx_name' => $uniqueFileName,  
            'file_path' => $targetPath  
        ];  
    } else {  
        // 插入失败  
        $response = [  
            'message' => '文件上传成功，但存入数据库失败',  
            'name' => $name
        ];  
    }  
  } else {  
      // 文件移动失败  
      $response = [  
          'message' => '文件上传失败',  
          'target_path' => $targetPath  
      ];  
  }  
}  
  
// 返回 JSON 响应  
echo json_encode($response);  
  
// 关闭数据库连接（如果不再需要）  
// $conn->close();  
?>