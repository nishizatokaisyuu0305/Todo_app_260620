<?php

session_start();
require_once __DIR__ . "/../config/database.php";
$keyword = trim($_GET["keyword"] ?? "");
$sort = $_GET["sort"] ?? "desc";

// データ取得
$orderBy = ($sort === "asc")
  ? "asc"
  : "desc";

if ($keyword === "") {
  $sql = "select * from todos order by created_at $orderBy";
  $stmt = $pdo->query($sql); 
} else {
  $sql = "select * from todos where title like ? order by created_at $orderBy";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(["%" . "$keyword" . "%"]);
}

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
  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
  />
  <title>Todoアプリ</title>
</head>
<body>
  <div class="container">
    <div class="page-card">
      <h1 class="page-title">
        <i class="fa-solid fa-list-check"></i>
        Todoリスト
      </h1>
      <?php if (isset($_SESSION["flash"])): ?>
        <div class="flash-message flash-<?= $_SESSION["flash"]["type"] ?>">
          <?= htmlspecialchars($_SESSION["flash"]["message"]) ?>
        </div>
        <?php unset($_SESSION["flash"]); ?>
      <?php endif; ?>
      <div class="todo-summary">
        <span>
          全体: <?= count($todos) ?>件
        </span>
        <span class="summary-incomplete">
          <i class="fa-regular fa-circle"></i>
          未完了: <?= count($incompleteTodos) ?>件
        </span>
        <span class="todo-summary-completed">
          <i class="fa-solid fa-circle-check"></i>
          完了済み: <?= count($completedTodos) ?>件
        </span>
      </div>

      <div class="form-card">
        <form action="create.php" method="POST" class="todo-form">
          <label for="title">新規タスク入力</label>
          <input 
            type="text" 
            name="title"
            class="todo-input"
            placeholder="タスクを入力してください"
          >
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            追加
          </button>
        </form>
      </div>
      <p>
        検索結果:
      <?= count($todos) ?>件
      </p>
      <form action="index.php" method="GET" class="search-form">
        <input 
        type="text"
        name="keyword"
        class="search-input"
        placeholder="タスクを検索"
        value="<?= htmlspecialchars($_GET["keyword"] ?? "") ?>"
        >

        <button type="submit">
          検索
        </button>
      </form>
      <form method="GET">
        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
        <label> 並び順 </label>
        <select name="sort" onchange="this.form.submit()">
          <option value="desc"
            <?= $sort === "desc" ? "selected" : "" ?>>
            新しい順
          </option>
          <option value="asc"
            <?= $sort === "asc" ? "selected" : "" ?>>
            古い順
          </option>
        </select>
      </form>
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
                  <i class="fa-regular fa-circle"></i>
                <?php else: ?>
                  <i class="fa-solid fa-circle-check"></i>
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
                <i class="fa-solid fa-pen"></i>
                編集
              </button>
            </form>
            <form action="delete.php" method="POST">
              <input
                type="hidden"
                name="id"
                value="<?= $todo["id"] ?>"
              >
              <button 
                type="submit" 
                class="btn btn-danger"
                onclick="return confirm('本当に削除しますか？')"
              >
                <i class="fa-solid fa-trash"></i>
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