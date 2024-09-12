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

try {
    // トランザクションの開始
    $pdo->beginTransaction();

    // GET メソッドを使用して、memosテーブルのidを取得
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        throw new Exception('Invalid memo ID');
    }

    // 削除するSQL文を準備
    $stmt = $pdo->prepare('DELETE FROM memos WHERE id = ? AND user_id = ?');

    // 削除するSQL文を実行
    if ($stmt->execute([$id, $_SESSION['user_id']])) {
        // コミット
        $pdo->commit();
        // ダッシュボードページで成功のメッセージを表示
        header('Location: ../dashboard.php?message=delete_success');
        exit();

    } else {
        throw new Exception('Failed to delete memo');
    }
} catch (Exception $e) {
    // ロールバック
    $pdo->rollBack();

    // エラーメッセージをログに書き込む
    error_log("Error in delete_memo.php: " . $e->getMessage(), 3, __DIR__ . '/../logs/php_errors.log');

    // ダッシュボードページで失敗のメッセージを表示
    header('Location: ../dashboard.php?message=delete_fail');
    exit();
}