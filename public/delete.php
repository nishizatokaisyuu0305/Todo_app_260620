<?php

session_start();
require_once __DIR__ . "/../config/database.php";

// ID取得・存在チェック(バリデーション)
$id = $_POST["id"];
$sql = "select * FROM todos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "対象のTodoが存在しません"
  ];

  header("Location: index.php");
  exit;
}


// 削除
$sql = "delete from todos where id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);



// 成功メッセージ
$_SESSION["flash"] = [
  "type" => "success",
  "message" => "タスクを削除しました"
];

// リダイレクト
header("Location: index.php");
exit;