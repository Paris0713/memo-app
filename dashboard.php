<?php
require(__DIR__ . '/includes/session.php');
require(__DIR__ . '/includes/db.php');
require(__DIR__ . '/includes/validation.php');
require(__DIR__ . '/includes/error_handling.php');

// セッションの確認
check_login();

// ユーザーのメモを取得
try {
    $stmt = $pdo->prepare('SELECT * FROM memos WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $memos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
    // デバッグ用: 取得したメモを確認
    // if (empty($memos)) {
    //     echo json_encode(['message' => 'メモが見つかりません。']);
    // } else {
    //     echo json_encode($memos); // 正常にメモが取得できた場合
    // }

    // JSON形式で結果を返す（APIとして利用する場合）
    // echo json_encode($memos);    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'データベースエラー: ' . $e->getMessage()]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Memo App</title>
    
</head>
<body>
    <div class="container">
        <h1>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</h1>
        <h2>あなたのメモ</h2>
        <ul>
            <?php if (empty($memos)): ?>
                <li>メモがありません。新しいメモを作成してください。</li>
            <?php else: ?>
                <?php foreach ($memos as $memo): ?>
                    <li>
                        <!-- 作成したメモのリンク -->
                        <h3><a href="view_memo.php?id=<?php echo $memo['id']; ?>"><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                        <p><?php echo nl2br(htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8')); ?></p>
                        <!-- メモの編集・削除リンク -->
                        <a href="./api/edit_memo.php?id=<?php echo $memo['id']; ?>">編集</a>
                        <a href="./api/delete_note.php?id=<?php echo $memo['id']; ?>">削除</a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h2>新しいメモを作成</h2>
        <form action="./api/create_memo.php" method="post">
            <div class="group">
                <label for="memo-title" class="label">タイトル</label>
                <input id="memo-title" type="text" class="input" name="title" required>
            </div>
            <div class="group">
                <label for="memo-content" class="label">内容</label>
                <textarea id="memo-content" class="input" name="content" required></textarea>
            </div>
            <div class="group">
                <label for="memo-tags" class="label">タグ</label>
                <input id="memo-tags" type="text" class="input" name="tags" placeholder="カンマ区切りで入力">
            </div>
            <div class="group">
                <input type="submit" class="button" value="作成">
            </div>
        </form>
    </div>
</body>
</html>
