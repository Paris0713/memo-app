<?php
// ファイルのインクルード
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

// ログイン確認 ログインしていない場合はログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// POSTリクエストが送信された場合 内容を取得し、データベースを更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare('UPDATE memos SET title = ?, content = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$title, $content, $id, $_SESSION['user_id']]);

    // 更新後、ダッシュボードページにリダイレクト
    header('Location: ../dashboard.php');
    exit();

    // ページが初めて読み込まれたときには GET メソッドを使用して、URLからメモのIDを取得
} else {
    $id = $_GET['id'];
    // PDOを使ってSQLでmemosテーブルに編集内容を挿入する準備
    $stmt = $pdo->prepare('SELECT * FROM memos WHERE id = ? AND user_id = ?');
    // SQLの実行
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
    <title>Edit Memo - Memo App</title>
</head>

<body>
    <div class="container">
        <h2>メモを編集</h2>
        <form action="edit_memo.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($memo['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="group">
                <label for="memo-title" class="label">タイトル</label>
                <input id="memo-title" type="text" class="input" name="title" value="<?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="group">
                <label for="memo-content" class="label">内容</label>
                <textarea id="memo-content" class="input" name="content" required><?php echo htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="group">
                <input type="submit" class="button" value="編集">
            </div>
        </form>
    </div>
</body>

</html>
