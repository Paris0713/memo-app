<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームからのデータを受け取る
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tags = $_POST['tags']; // タグも受け取る場合

    try {
        // 新しいメモをデータベースに挿入
        $stmt = $pdo->prepare('INSERT INTO memos (user_id, title, content, tags) VALUES (?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $title, $content, $tags]);

        // メモ作成後、ダッシュボードにリダイレクト
        header('Location: ../dashboard.php');
        exit();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'データベースエラー: ' . $e->getMessage()]);
        exit();
    }
}


// デバッグ用：メモの情報を表示
echo '<pre>';
print_r($memo);
echo '</pre>';

?>
