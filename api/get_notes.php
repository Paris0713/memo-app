<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインしてください']);
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM notes WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();

echo json_encode($notes);
?>
