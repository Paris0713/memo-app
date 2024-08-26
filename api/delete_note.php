<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインしてください']);
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
if ($stmt->execute([$id, $_SESSION['user_id']])) {
    echo json_encode(['message' => 'メモの削除が成功しました']);
} else {
    echo json_encode(['error' => 'メモの削除に失敗しました']);
}
?>
