<?php
require(__DIR__ . '/../includes/session.php');
require(__DIR__ . '/../includes/db.php');

header('Content-Type: application/json; charset=utf-8');

// ログインしているか確認
check_login();

try {
    // ユーザーのメモを取得
    $stmt = $pdo->prepare('SELECT * FROM memos WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $memos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON形式で結果を返す
    echo json_encode($memos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'データベースエラー: ' . $e->getMessage()]);
    exit();
}
?>
