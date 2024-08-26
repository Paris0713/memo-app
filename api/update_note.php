<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインしてください']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare('UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?');
    if ($stmt->execute([$title, $content, $id, $_SESSION['user_id']])) {
        echo json_encode(['message' => 'メモの更新が成功しました']);
    } else {
        echo json_encode(['error' => 'メモの更新に失敗しました']);
    }
}
?>
