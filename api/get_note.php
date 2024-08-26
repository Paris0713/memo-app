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
$stmt = $pdo->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$note = $stmt->fetch();

echo json_encode($note);
?>
