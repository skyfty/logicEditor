<?php
// upload.php

$servername = "8.141.89.131";
$username = "root";
$password = "123456";
$dbname = "lvpenghui";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $title = $_POST["title"];
    $base64Image = $_POST["box"]; // 获取前端上传的 base64 编码的图片数据
    $decodedImage = base64_decode($base64Image);
	echo $title;
    // Determine file extension based on $title
    switch($title) {
        case 'music':
            $extension = 'mp3';
            break;
        case 'crop':
        case 'images':
            $extension = 'jpg';
            break;
        case 'video':
            $extension = 'mp4';
            break;
        case 'animation':
            $extension = 'gif';
            break;
        default:
            $extension = 'mp4'; // Default extension
            break;
    }
	
    $fileName = uniqid() . "." . $extension;

    // 要保存图片的目录路径
    $directoryPath = "$title/";

    if (!is_dir($directoryPath)) {
        // 如果文件夹不存在，则创建
        if (mkdir($directoryPath, 0755, true)) {
            echo "目录创建成功";
        } else {
            echo "目录创建失败";
            exit();
        }
    }else{
      echo '存在';
    }
    $filePath = $directoryPath . $fileName;

    if (file_put_contents($filePath, $decodedImage) !== false) {
        // 将图片信息插入数据库
        $sql = "INSERT INTO $title (filename, filepath,name) VALUES ('$fileName', '$filePath','$name')";
        
        if ($conn->query($sql) === TRUE) {
            echo "上传成功";
        } else {
            echo "上传失败".$filename.$directoryPath.$name;
        }
    } else {
        echo "Error writing file: " . $fileName;
    }
} else {
    echo '请求方式错误';
}

$conn->close();
?>