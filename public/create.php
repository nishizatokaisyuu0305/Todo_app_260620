<?php

session_start();
require_once __DIR__ . "/../config/database.php";


// titleデータ取得
$title = trim($_POST["title"]);


// 存在チェック
$sql = "select * from todos where title = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

// バリデーション（空白チェック）
if ($title === "") {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "タイトルを入力してください"
  ];

  header("Location: index.php");
  exit;
}


// 重複チェック
$sql = "select * from todos where title = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title]);
$existingTodo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingTodo) {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "同じTodoが既に存在します"
  ];

  header("Location: edit.php");
  exit;
}


// 登録
$sql = "INSERT INTO todos (title) VALUES (?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title]);


// 成功メッセージ
$_SESSION["flash"] = [
  "type" => "success",
  "message" => "タスクを更新しました"
];

header("Location: index.php");
exit;