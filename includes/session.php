<?php
// セッションを開始
session_start();

// ユーザーがログインしていない場合、エラーメッセージを返す
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['message' => 'ログインが必要です']);
        exit();
    }
}
?>
