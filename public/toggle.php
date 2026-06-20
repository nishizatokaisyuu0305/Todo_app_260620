<?php

require_once __DIR__ . "/../config/database.php";

// id取得
$id = $_POST["id"];

// status取得
$sql = "
SELECT status
from todos
WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

// status設定
if ($todo["status"] == 0) {
  $newStatus = 1;
} else {
  $newStatus = 0;
}

// UPDATE
$sql = "
UPDATE todos
SET status = ?
WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$newStatus, $id]);

// リダイレクト
header("Location: index.php");
exit;