<?php

// ファイルのインクルード
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

// JSONレスポンスを返すためのヘッダー設定
header('Content-Type: application/json; charset=utf-8');
// ログインの確認
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームからのデータを受け取る
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tags = $_POST['tags'];

    try {
        // トランザクションの開始
        $pdo->beginTransaction();

        // 新しいメモをデータベースに挿入
        $stmt = $pdo->prepare('INSERT INTO memos (user_id, title, content) VALUES (?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $title, $content]);

        // 挿入されたメモのIDを取得
        $memoId = $pdo->lastInsertId();

        // タグの処理
        if (!empty($tags)) {
            // タグをカンマで分割して配列にする
            $tagArray = explode(',', $tags);
            foreach ($tagArray as $tag) {
                // タグの前後の空白を削除
                $tag = trim($tag);

            // タグが既に存在するか確認
            $stmt = $pdo->prepare('SELECT id FROM tags WHERE name = ?');
            $stmt->execute([$tag]);
            $tagId = $stmt->fetchColumn();

            // タグが存在しない場合は新しく作成
            if (!$tagId) {
                $stmt = $pdo->prepare('INSERT INTO tags (name) VALUES (?)');
                $stmt->execute([$tag]);
                $tagId = $pdo->lastInsertId();
            }

            // memo_tagsテーブルにエントリを追加
            $stmt = $pdo->prepare('INSERT INTO memo_tags (memo_id, tag_id) VALUES (?, ?)');
            $stmt->execute([$memoId, $tagId]);
            }
        }

        // トランザクションのコミット
        $pdo->commit();

        // メモ作成後、ダッシュボードにリダイレクト
        header('Location: ../dashboard.php');
        exit();


    } catch (PDOException $e) {
        // トランザクションのロールバック
        $pdo->rollBack();
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
