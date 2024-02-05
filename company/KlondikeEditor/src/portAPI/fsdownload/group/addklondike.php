<?php
// getImages.php
$servername = "8.141.89.131";
$username = "root";
$password = "hui.1234";
$dbname = "KlondikeEditor";

// 设置响应头允许跨域请求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$levelGroup_id = $_POST['levelGroup_id'];
$ids = $_POST['ids'];

$idsArray = json_decode($ids, true);

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
    // 获取刚插入的数据的ID
    $lastInsertedId = $conn->insert_id;
    $success = true; // 记录修改是否成功
    foreach ($idsArray as $id) {
        $a = '关卡' . $id['id'];
        $b = $id['id'] - 1;

        // 获取levelGroupId数据数量
        $sequenceSql = "SELECT MIN(sequenceId) as minSequenceId FROM sequence_map WHERE levelGroupId = $levelGroup_id";

        $sequenceResult = $conn->query($sequenceSql);
        $sequenceRow = $sequenceResult->fetch_assoc();
        $sequenceId = $sequenceRow['minSequenceId'];

        if ($sequenceId === null) {
            $sequenceCountSql = "SELECT COUNT(*) as count FROM poke WHERE levelGroupId = $levelGroup_id";

            $sequenceCountResult = $conn->query($sequenceCountSql);
            $sequenceCount = $sequenceCountResult->fetch_assoc()['count'];
            $sequenceId = $sequenceCount + 1;
        } else {
            // 删除sequence_map表中的对应记录
            $deleteSql = "DELETE FROM sequence_map WHERE levelGroupId = $levelGroup_id AND sequenceId = $sequenceId";
            $deleteResult = $conn->query($deleteSql);
            if (!$deleteResult) {
                $success = false;
                break;
            }
        }

        // 插入到poke表
        $pokeSql = "INSERT INTO poke 
                    (klondikeId, difficultyLevel, name, list, deckGameId, gold, experience, diamond, prop, stamina, levelGroupId, sequenceId) 
                VALUES 
                    ('$lastInsertedId', '{$id['difficultyLevel']}', '$a', '$b', '{$id['deckGame']}', '{$id['gold']}', '{$id['experience']}', '{$id['diamond']}', '{$id['prop']}', '{$id['stamina']}','$levelGroup_id','$sequenceId')";
        $pokeResult = $conn->query($pokeSql);

        if (!$pokeResult) {
            $success = false;
            break;
        }
    }
    if ($success) {
        echo '修改成功';
    } else {
        echo '修改失败'.$lastInsertedId.$id['difficultyLevel'].$a.$b.$id['deckGame'];
    }
} else {
    echo "增加失败".'--'.$name,$levelGroup_id,$sequenceIds;
}

$conn->close();
?>