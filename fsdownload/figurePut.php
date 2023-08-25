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
  $name = $_POST['name'];
  $images = $_POST['images'];
  $role = $_POST['role'];
  $property = $_POST['property'];
  $dialogue = $_POST['dialogue'];
  $children = $_POST['children'];

  $amount1 = $_POST['amount1'];
  $amount2 = $_POST['amount2'];
  $article1 = $_POST['article1'];
  $article2 = $_POST['article2'];
  $compound = $_POST['compound'];


      switch($children) {
        case 'dialogue':
            $sql = "INSERT INTO $children (dialogue) VALUES ('$dialogue')";
            break;
        case 'expression':
        	  $sql = "INSERT INTO $children (name, images,role) VALUES ('$name', '$images','$role')";
        	  break;
        case 'role':
            $sql = "INSERT INTO $children (name, images, property) VALUES ('$name', '$images', '$property')";
            break;
        case 'article':
            $sql = "INSERT INTO $children (name, images, amount) VALUES ('$name', '$images', '$property')";
            break;
        case 'compound':
            $sql = "INSERT INTO $children (amount1, amount2, article1, article2, compound) VALUES ('$amount1', '$amount2','$article1','$article2', '$compound')";
            break;
        default:
            echo '修改失败';
            break;
    }

//      $sql = "INSERT INTO $children (name, images, property) VALUES ('$name', '$images', '$property')";
      $result = $conn->query($sql);
      if ($result === TRUE) {
        echo '增加成功'. $dialogue;
      } else {
        echo "增加失败".$children.$name.$images.$property;
      }

  // 关闭数据库连接
  $conn->close();
?>
