<?php

require_once __DIR__ . "/../config/database.php";

// id情報取得・編集SQL実行
$id = $_GET["id"];
$sql = " SELECT * FROM todos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- html表示 -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>編集</title>
</head>
<body>
  <div class="container">
    <h1 class="page-title">
      Todo編集
    </h1>

    <form action="update.php" method="POST" class="todo-form">
      <input type="hidden" name="id" value="<?= $todo["id"] ?>">
      <input 
        type="text" 
        name="title" 
        value="<?= htmlspecialchars($todo["title"]) ?>"
        class="todo-input"
      >
      <div class="button-group">
        <button type="submit" class="btn btn-primary">
          更新
        </button>
        <a href="index.php" class="btn btn-secondary">
          戻る
        </a>
      </div>
    </form>
  </div>
</body>
</html>
