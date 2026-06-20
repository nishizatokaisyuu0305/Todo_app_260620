<?php

require_once __DIR__ . "/../config/database.php";

$id = $_POST["id"];
$title = $_POST["title"];
$sql = "
UPDATE todos
SET title = ?
WHERE id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$title, $id]);


header("Location: index.php");
exit;