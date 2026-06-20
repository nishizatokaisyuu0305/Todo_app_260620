<?php

require_once __DIR__ . "/../config/database.php";

// データ取得
$sql = "select * from todos";
$stmt = $pdo->query($sql);
$todos = $stmt->fetchALL(PDO::FETCH_ASSOC);

// 未完了取得,完了済み取得
$incompleteTodos = [];
$completedTodos = [];

foreach ($todos as $todo) {
  if ($todo["status"] == 0) {
    $incompleteTodos[] = $todo;
  } else {
    $completedTodos[] = $todo;
  }
}
?>

<!-- html表示 -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Todoアプリ</title>
</head>
<body>
  <div class="container">
    <h1>Todoリスト</h1>

    <form action="create.php" method="POST" class="todo-form">
      <label for="title">新規タスク入力</label>
      <input 
        type="text" 
        name="title"
        placeholder="タスクを入力してください"
      >
      <button type="submit" class="add-btn">追加</button>
    </form>
    <ul>
    <?php foreach ($todos as $todo): ?>
      <li class="todo-item">
        <!-- タスク情報 -->
        <div class="todo-content">
          <div class="todo-title">
            <?= htmlspecialchars($todo["title"]) ?>
          </div>
          <div class="todo-date">
            <?= date("Y-m-d H:i", strtotime($todo["created_at"])) ?>
          </div>
        </div>
        <!-- ボタン群 -->
        <div class="todo-actions">
          <form action="toggle.php" method="POST">
            <input
              type="hidden"
              name="id"
              value="<?= $todo["id"] ?>"
            >
            <button type="submit">
              <?php if ($todo["status"] == 0): ?>
                ❌
              <?php else: ?>
                ☑️
              <?php endif; ?>
            </button>
          </form>
          <form action="edit.php" method="GET">
            <input
              type="hidden"
              name="id"
              value="<?= $todo["id"] ?>"
            >
            <button type="submit" class="edit-btn">
              編集
            </button>
          </form>
          <form action="delete.php" method="POST">
            <input
              type="hidden"
              name="id"
              value="<?= $todo["id"] ?>"
            >
            <button type="submit" class="delete-btn">
              削除
            </button>
          </form>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>

    <h2>未完了タスク</h2>
    <ul>
      <?php foreach ($incompleteTodos as $todo): ?>
        <li>
          ❌
          <?= htmlspecialchars($todo["title"]) ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <h2>完了済みタスク</h2>
    <ul>
      <?php foreach ($completedTodos as $todo): ?>
        <li class="completed">
          <?= htmlspecialchars($todo["title"]) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>