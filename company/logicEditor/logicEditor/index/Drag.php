<?php
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

  $id = $_POST['id'];
  $children = $_POST['children'];
  $list = $_POST['list'];
  $oldList = $_POST['oldList'];
  $klondikeId = $_POST['klondikeId'];

  	  	switch($children) {
          case 'poke':

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

  $conn->close();
?>
