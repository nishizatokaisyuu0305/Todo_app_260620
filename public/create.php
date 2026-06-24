<?php

session_start();
require_once __DIR__ . "/../config/database.php";


// title,due_date取得
$title = trim($_POST["title"]);
$dueDate = $_POST["due_date"] ?: null;

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
$sql = "
select * 
from todos 
where title = ?
and user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $_SESSION["user_id"]]);
$existingTodo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingTodo) {
  $_SESSION["flash"] = [
    "type" => "error",
    "message" => "同じTodoが既に存在します"
  ];

  header("Location: index.php");
  exit;
}


// 登録
$sql = "
  INSERT INTO todos (
  title,
  user_id,
  due_date
  ) 
  VALUES (?, ?, ?)
  ";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $title,
  $_SESSION["user_id"],
  $dueDate
  ]);


// 成功メッセージ
$_SESSION["flash"] = [
  "type" => "success",
  "message" => "タスクを追加しました"
];

header("Location: index.php");
exit;