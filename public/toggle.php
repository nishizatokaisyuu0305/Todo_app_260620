<?php

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login_form.php");
  exit;
}
require_once __DIR__ . "/../config/database.php";


// id/statusデータ取得・実行
$id = $_POST["id"];
if (!isset($_POST["id"])) {
  header("Location: index.php");
  exit;
}
$sql = "
SELECT title, status
from todos
WHERE id = ?
and user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $id,
  $_SESSION["user_id"]
  ]);
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
and user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $newStatus,
  $id,
  $_SESSION["user_id"]
  ]);


// フラッシュメッセージ
$_SESSION["flash"] = [
  "type" => "success",
  "message" => "「" . $todo["title"] . "」の状態を更新しました。"
];


// リダイレクト
header("Location: index.php");
exit;