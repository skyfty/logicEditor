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

// 获取要删除的记录的ID（假设从前端传递过来）
$id = 1;
$ids = 10;
$name = $_POST['name'];
$children = 'poke';

// 构建SQL更新语句
if ($ids != '') {
  // 查询 id1 和 id2 所在数据的 name 字段的值
  $selectSql = "SELECT name FROM $children WHERE id IN ('$id', '$ids')";
  $result = $conn->query($selectSql);
  
  if ($result->num_rows === 2) {
      $rows = $result->fetch_all(MYSQLI_ASSOC);
      $name1 = $rows[0]['name'];
      $name2 = $rows[1]['name'];

      // 更新数据，交换 name 字段的值
      if ($id > $ids) {
        $updateSql1 = "UPDATE $children SET name = '$name1' WHERE id = '$id'";
        $updateSql2 = "UPDATE $children SET name = '$name2' WHERE id = '$ids'";
      } else {
        $updateSql1 = "UPDATE $children SET name = '$name2' WHERE id = '$id'";
        $updateSql2 = "UPDATE $children SET name = '$name1' WHERE id = '$ids'";
      }

      if ($conn->query($updateSql1) === TRUE && $conn->query($updateSql2) === TRUE) {
          echo "切换操作成功" . $name1 . $name2;
      } else {
          echo "切换操作失败: " . $conn->error;
      }
  } else {
      echo "未找到匹配的数据";
  }
} else {
  $sql = "UPDATE $children SET name='$name' WHERE id = '$id'";
  if ($conn->query($sql) === TRUE) {
      echo "记录修改成功";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// 关闭数据库连接
$conn->close();
?>