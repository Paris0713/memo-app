<?php
// ファイルのインクルード
require(__DIR__ . '/../includes/session.php');
require(__DIR__ . '/../includes/db.php');
require(__DIR__ . '/../includes/validation.php');
require(__DIR__ . '/../includes/error_handling.php');

// JSONレスポンスを返すためのヘッダー設定
// header('Content-Type: application/json; charset=utf-8');


// ログイン状態を確認 ログインしていない場合、エラーメッセージを返す
check_login();

// POSTリクエストが送信された場合 内容を取得し、データベースを更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        // トランザクションを開始
        $pdo->beginTransaction();

        // プリペアドステートメントの準備と実行
        $stmt = $pdo->prepare('UPDATE memos SET title = ?, content = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$title, $content, $id, $_SESSION['user_id']]);

        // トランザクションをコミット
        $pdo->commit();

        // 更新後、ダッシュボードページにリダイレクト
        header('Location: ../dashboard.php');
        exit();
    } catch (Exception $e) {
        // トランザクションをロールバック
        $pdo->rollBack();

        // エラーメッセージをログに書き込む
        error_log("Error in edit_memo.php " . $e->getMessage(), 3, __DIR__ . '/../logs/php_errors.log');
        // エラーメッセージを表示
        echo json_encode(['error' => 'メモの更新に失敗しました。']);
        exit();
    }

    // ページが初めて読み込まれたときには GET メソッドを使用して、URLからメモのIDを取得
} else {
    $id = $_GET['id'];
    // プリペアドステートメントでmemosテーブルに編集内容を挿入する準備と実行
    $stmt = $pdo->prepare('SELECT * FROM memos WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $memo = $stmt->fetch();
    // 成功したらダッシュボードページへリダイレクト
    if (!$memo) {
        header('Location: ../dashboard.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDGothic:wght@400;700&family=Cactus+Classical+Serif&family=IBM+Plex+Sans+JP:wght@100;200;300;400;500;600;700&family=M+PLUS+Rounded+1c:wght@100;300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Edit Memo - Memo App</title>
</head>

<body>
    <div class="container">
        <div class="edit">
            <dev class="edit-header">
                <h2 class="edit-h2">メモを編集</h2>
            </dev>
            <div class="edit-area">
                <form action="edit_memo.php" method="post">
                    <input type="hidden" name="id"
                        value="<?php echo htmlspecialchars($memo['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="edit-group">
                        <label for="memo-title" class="label">タイトル</label>
                        <input id="memo-title" type="text" class="edit-input" name="title"
                            value="<?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="edit-group">
                        <label for="memo-content" class="label">内容</label>
                        <textarea id="memo-content" class="input edit-textarea" name="content"
                            required><?php echo htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="edit-group">
                        <input type="submit" class="edit-button" value="編集">
                    </div>
                </form>

            </div>
            <dev class="edit-footer">

                <a class="foot-lnk" href="../dashboard.php">ダッシュボードページへ戻る</a>
            </dev>
        </div>
    </div>
</body>

</html>