<?php

session_start();
require_once __DIR__ . "/../config/database.php";


// id/statusデータ取得・実行
$id = $_POST["id"];
$sql = "
SELECT title, status
from todos
WHERE id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);


// バリデーション
if (!$todo) {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "対象のTodoが存在しません"
  ];

  header("Location: index.php");
  exit;
}


// status切替
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


// フラッシュメッセージ


$_SESSION["flash"] = [
  "type" => "success",
  "message" => "「" . $todo["title"] . "」の状態を更新しました。"
];


// リダイレクト
header("Location: index.php");
exit;