<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

  // 查询数据库并返回数据
  $id = $_POST['id'];
  $children = $_POST['children'];
  $level = $_POST['level'];

  	  	switch($children) {
          case 'poke':
                  $sql = "UPDATE $children SET difficultyLevel='$level' WHERE id = '$id'";
          break;
	        default:
	            echo '修改失败';
	            break;
	    }

      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo $children.'增加成功';
      } else {
        echo $children.'--'.$level.$id."-增加失败";
      }

  // 关闭数据库连接
  $conn->close();
?>
