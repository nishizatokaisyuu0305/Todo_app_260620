<?php

require_once __DIR__ . "/../config/database.php";

// ID取得・削除SQL設定
$id = $_POST["id"];
$sql = "
DELETE FROM todos
WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);


// リダイレクト
header("Location: index.php");
exit;