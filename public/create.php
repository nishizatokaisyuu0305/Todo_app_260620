<?php

require_once __DIR__ . "/../config/database.php";

$title = $_POST["title"];

// バリデーション（空白チェック）
if (trim($title) === "") {
  echo "タイトルを入力してください";
  exit;
}

// 重複チェック
$sql = "
select *
from todos
where title = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$title]);

$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($todo) {
  echo "同じTodoが既に存在します";
  exit;
}

// インサート
$sql = "INSERT INTO todos (title) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title]);


header("Location: index.php");
exit;