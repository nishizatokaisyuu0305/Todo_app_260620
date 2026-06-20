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
  <title>編集</title>
</head>
<body>
  
  <h1>Todo編集</h1>

  <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?= $todo["id"] ?>">
    <input type="text" name="title" value="<?= htmlspecialchars($todo["title"]) ?>">
    <button type="submit">
      更新
    </button>
  </form>
</body>
</html>
