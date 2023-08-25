<?php
  // 连接到数据库
  $servername = "8.141.89.131";
  $username = "root";
  $password = "123456";
  $dbname = "lvpenghui";

  header("Access-Control-Allow-Origin: http://localhost:8080");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  $conn = new mysqli($servername, $username, $password, $dbname);

  // 检查连接是否成功
  if ($conn->connect_error) {
      die("连接数据库失败: " . $conn->connect_error);
  }

  // 查询数据库并返回数据
  $id = $_POST['id'];
  $name = $_POST['name'];
  $dialogue = $_POST['dialogue'];
  $amount = $_POST['amount'];
  $images = $_POST['images'];
  $property = $_POST['property'];
  $children = $_POST['children'];

  $amount1 = $_POST['amount1'];
  $amount2 = $_POST['amount2'];
  $article1 = $_POST['article1'];
  $article2 = $_POST['article2'];
  $compound = $_POST['compound'];




    if($id !== null && $children !== null){

    	 switch($children) {
        case 'plot':
            $sql = "UPDATE $children SET name='$name',property='$property'  WHERE id = $id";
            break;
        case 'expression':
        	  $sql = "UPDATE $children SET name='$name',role='$property',images='$images'  WHERE id = $id";
        	  break;
        case 'role':
            $sql = "UPDATE $children SET name='$name',property='$property',images='$images'  WHERE id = $id";
            break;
        case 'dialogue':
            $sql = "UPDATE $children SET dialogue='$dialogue'  WHERE id = $id";
            break;
       case 'article':
            $sql = "UPDATE $children SET amount='$amount',name='$name',images='$images'  WHERE id = $id";
            break;
       case 'compound':
            $sql = "UPDATE $children SET amount1='$amount1', amount2='$amount2', article1='$article1', article2='$article2', compound='$compound' WHERE id = $id";
            break;
        default:
            echo '修改失败';
            break;
    }

      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo $children.'修改成功';
      } else {
        echo $children."修改失败";
      }
    }else{
      echo '缺少参数';
    }

  // 关闭数据库连接
  $conn->close();
?>
