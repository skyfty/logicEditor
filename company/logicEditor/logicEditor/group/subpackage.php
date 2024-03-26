<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

$ids = $_POST['ids'];
$name = $_POST['name'];
$deleteId = $_POST['deleteId'];
$levelGroup_id = $_POST['levelGroup_id'];

$idsArray = json_decode($ids, true);

$success = true;

foreach ($idsArray as $key => $group) {

    if($key != 0){
        // 获取levelGroupId数据数量
        $sequenceSqls = "SELECT MIN(sequenceId) as minSequenceId FROM sequenceGroup WHERE levelGroupId = $levelGroup_id";
    
        $sequenceResults = $conn->query($sequenceSqls);
        $sequenceRows = $sequenceResults->fetch_assoc();
        $sequenceIds = $sequenceRows['minSequenceId'];
    
        if ($sequenceIds === null) {
            $sequenceCountSqls = "SELECT COUNT(*) as count FROM klondike WHERE levelGroupId = $levelGroup_id";
    
            $sequenceCountResults = $conn->query($sequenceCountSqls);
            $sequenceCounts = $sequenceCountResults->fetch_assoc()['count'];
            $sequenceIds = $sequenceCounts + 1;
        } else {
            // 删除sequence_map表中的对应记录
            $deleteSqls = "DELETE FROM sequenceGroup WHERE levelGroupId = $levelGroup_id AND sequenceId = $sequenceIds";
            $deleteResults = $conn->query($deleteSqls);
            if (!$deleteResults) {
                echo '删除成功';
            }
        }

        $names = $name.'--'.$key;
        $insertSql = "INSERT INTO klondike (name, levelGroupId, sequenceId) VALUES ('$names','$levelGroup_id','$sequenceIds')";
        $result = $conn->query($insertSql);
        if ($result) {
            // 获取刚插入的数据的ID
            if($key != 0){
                $lastInsertedId = $conn->insert_id;
                foreach ($group as $id) {
                    // 插入到poke表
                    $pokeSql = "UPDATE poke SET klondikeId = '$lastInsertedId'  WHERE id = '$id'";
                    $pokeResult = $conn->query($pokeSql);
    
                    if (!$pokeResult) {
                        $success = false;
                        break;
                    }
                }
            }
        } else {
            echo "增加失败";
        }
      }
}
    if ($success) {
        echo '分包成功';
    } else {
        echo '修改失败';
    }


$conn->close();
?>