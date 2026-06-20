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
    <div class="page-card">
      <h1 class="page-title">
        Todoリスト
      </h1>

      <div class="form-card">
        <form action="create.php" method="POST" class="todo-form">
          <label for="title">新規タスク入力</label>
          <input 
            type="text" 
            name="title"
            class="todo-input"
            placeholder="タスクを入力してください"
          >
          <button type="submit" class="btn btn-primary">追加</button>
        </form>
      </div>
      <ul>
      <?php foreach ($todos as $todo): ?>
        <li class="todo-item <?= $todo["status"] == 1 ? 'completed-card' : '' ?>">
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
              <button type="submit" class="btn btn-success">
                編集
              </button>
            </form>
            <form action="delete.php" method="POST">
              <input
                type="hidden"
                name="id"
                value="<?= $todo["id"] ?>"
              >
              <button type="submit" class="btn btn-danger">
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
  </div>
</body>
</html>