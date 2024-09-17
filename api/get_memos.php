<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 必要なファイルのインクルード
require(__DIR__ . '/../includes/session.php');
require(__DIR__ . '/../includes/db.php');
require(__DIR__ . '/../includes/validation.php');
require(__DIR__ . '/../includes/error_handling.php');

header('Content-Type: application/json; charset=utf-8');

// ログインしているか確認
check_login();

// オフセットとリミットの取得
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

// ユーザーIDを取得
$user_id = $_SESSION['user_id'] ?? null; // nullの場合を考慮

// デバッグ用の出力
echo "User ID: " . var_export($user_id, true) . "\n"; // user_idの値を表示

if ($user_id === null) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'User ID is not set.']);
    exit();
}

try {
    // SQL文の準備
    $sql = "SELECT * FROM memos WHERE user_id = $user_id LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql); // ここはprepareしなくても良いかもしれません

    // SQL文を出力
    echo "Executing SQL: $sql\n";

    // パラメータを設定して実行
    $stmt->execute();

    $memos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON形式で結果を返す
    echo json_encode($memos, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'データベースエラー: ' . $e->getMessage()]);
    exit();
}
