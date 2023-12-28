<?php
  // 连接到数据库
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

  // 设置响应头允许跨域请求
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type");

  $conn = new mysqli($servername, $username, $password, $dbname);

  // 检查连接是否成功
  if ($conn->connect_error) {
      die("连接数据库失败: " . $conn->connect_error);
  }

  // 查询数据库并返回数据
  $name = $_POST['name'];
  $children = $_POST['children'];
  $level = $_POST['level'];

  	  	switch($children) {
	        case 'level_difficulty':
	            	  $checkQuery = "SELECT * FROM $children WHERE name = '$name'";
				  $checkResult = $conn->query($checkQuery);
				  if ($checkResult->num_rows > 0) {
				      echo "名字已存在，无法新增记录";
				      exit;
				  }else{
				     $sql = "INSERT INTO level_difficulty (name) VALUES ('$name')";
				     echo '插入-';
				  }
			break;
			case 'level':
				     $sql = "INSERT INTO level (name,level) VALUES ('$name','$level')";
			break;

	        default:
	            echo '修改失败';
	            break;
	    }

      
      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo $children.'增加成功';
      } else {
        echo $children.'--'.$name."-增加失败";
      }

  // 关闭数据库连接
  $conn->close();
?>
