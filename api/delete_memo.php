<?php
// ファイルのインクルード
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

// JSONレスポンスを返すためのヘッダー設定
header('Content-Type: application/json; charset=utf-8');

// ログイン状態を確認 ログインしていない場合、エラーメッセージを返す
check_login();

// GET メソッドを使用して、memosテーブルのidを取得
$id = $_GET['id'];
// 削除するSQL文を準備
$stmt = $pdo->prepare('DELETE FROM memos WHERE id = ? AND user_id = ?');
// 削除するSQL文を実行
if ($stmt->execute([$id, $_SESSION['user_id']])) {
    // ダッシュボードページで成功のメッセージを表示
    header('Location: ../dashboard.php?message=delete_success');
    exit();
} else {
    // ダッシュボードページで失敗のメッセージを表示
    header('Location: ../dashboard.php?message=delete_fail');
}
exit();

?>


