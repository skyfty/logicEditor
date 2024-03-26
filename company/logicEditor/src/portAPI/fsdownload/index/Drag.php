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

  $id = $_POST['id'];
  $children = $_POST['children'];
  $list = $_POST['list'];
  $oldList = $_POST['oldList'];
  $klondikeId = $_POST['klondikeId'];
//   $id = 92;
//   $children = 'klondike';
//   $list = 4;
//   $oldList = 1;
//   $klondikeId = 28;

  	  	switch($children) {
          case 'poke':
                  // 更新满足条件的数据
                  $updateSql =  "UPDATE $children 
                  SET list = IF($list < $oldList, 
                                IF(list <= $oldList AND list >= $list, list + 1, list),
                                IF(list >= $oldList AND list <= $list, list - 1, list))
                  WHERE KlondikeId = '$klondikeId';
                  UPDATE $children SET list = $list WHERE id = $id";
              if ($conn->multi_query($updateSql)) {
                echo "更新操作成功";
              } else {
                echo "更新操作失败";
              }
          break;
          case 'klondike':
                  // 更新满足条件的数据
                  $updateSql =  "UPDATE klondike 
                  SET sequenceId = IF($list < $oldList, 
                                IF(sequenceId <= $oldList AND sequenceId >= $list, sequenceId + 1, sequenceId),
                                IF(sequenceId >= $oldList AND sequenceId <= $list, sequenceId - 1, sequenceId))
                  WHERE levelGroupId = $klondikeId;
                  UPDATE klondike SET sequenceId = $list WHERE id = $id";
              if ($conn->multi_query($updateSql)) {
                echo "更新操作成功";
              } else {
                echo "更新操作失败".$children.'--'.$id.'--'.$klondikeId.'--'.$list.'--'.$oldList;
              }
          break;
	        default:
	            echo '修改失败';
	            break;
	    }
      if($sql){
        $result = $conn->query($sql);
        if ($result === TRUE) {
          echo $children.'更新操作成功';
        } else {
          echo $children.'--'.$name."-更新操作成功";
        }
      }

  // 关闭数据库连接
  $conn->close();
?>
