<?php

session_start();
require_once __DIR__ . "/../config/database.php";

// id/タイトルデータ取得・sql設定
$id = $_POST["id"];
$title = $_POST["title"];
$title = trim($_POST["title"]);
$sql = "
UPDATE todos
SET title = ?
WHERE id = ?
";

// バリデーション（空文字チェック）
if ($title === "") {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "タイトルを入力して下さい"
  ];

  header("Location: edit.php?id=" . $id);
  exit;
}


// 重複チェック
$sql = "select * from todos where title = ? and id != ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($todo) {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "同じTodoが既に存在します"
  ];

  header("Location: edit:php?id=" . $id);
}


// 登録
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $id]);
$_SESSION["flash"] = [
  "type" => "success",
  "message" => "タスクを更新しました"
];


header("Location: index.php");
exit;