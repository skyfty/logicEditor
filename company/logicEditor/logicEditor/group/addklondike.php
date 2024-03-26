<?php
 // 设置响应头允许跨域请求
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: Content-Type");
 header('Content-Type: application/json');

require_once('../Api.php');

$levelGroup_id = $_POST['levelGroup_id']; 

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

// 使用内联视图计算记录数量并插入新数据
$name = '关卡包'.$sequenceIds;
$insertSql = "INSERT INTO klondike (name, levelGroupId, sequenceId) VALUES ('$name','$levelGroup_id','$sequenceIds')";
$result = $conn->query($insertSql);

if ($result) {
    echo '增加成功';
} else {
    echo "增加失败".'--'.$name,$levelGroup_id,$sequenceIds;
}

$conn->close();
?>