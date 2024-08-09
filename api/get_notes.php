<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインしてください']);
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM notes WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();

echo json_encode($notes);
?>
