<?php
require(__DIR__ . '/includes/session.php');
require(__DIR__ . '/includes/db.php');
require(__DIR__ . '/includes/validation.php');
require(__DIR__ . '/includes/error_handling.php');

// セッションの確認
check_login();

// ユーザーのメモを取得
try {
    $stmt = $pdo->prepare('SELECT * FROM memos WHERE user_id = ? LIMIT 3');
    $stmt->execute([$_SESSION['user_id']]);
    $memos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // デバッグ用: 取得したメモを確認
    // var_dump($memos);


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

// メッセージを表示させる
$message = '';
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'delete_success') {
        $message = 'メモの削除が成功しました。';
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
    
    <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/dashboard.css">
    <title>Dashboard - Memo App</title>

</head>

<body>
    <div class="dashboard-container">
        <header class="header">
            <h1>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</h1>

            <!-- メッセージを表示させるdiv -->
            <?php if (isset($_GET['message'])): ?>
                <?php if ($_GET['message'] == 'delete_success'): ?>
                    <div class="alert alert-success">メモの削除に成功しました。</div>
                <?php elseif ($_GET['message'] == 'delete_fail'): ?>
                    <div class="alert alert-danger">メモの削除に失敗しました。</div>
                <?php endif; ?>

            <?php endif; ?>
        </header>

        <div class="nav">

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
                            <a href="./api/delete_memo.php?id=<?php echo $memo['id']; ?>">削除</a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div>
        <div class="main">

            <h2>新しいメモを作成</h2>
            <form action="./api/create_memo.php" method="post">
                <div class="memo-group">
                    <label for="memo-title" class="memo-label">タイトル</label>
                    <input id="memo-title" type="text" class="memo-input" name="title" required>
                </div>
                <div class="memo-group">
                    <label for="memo-content" class="memo-label">内容</label>
                    <textarea id="memo-content" class="memo-input" name="content" required></textarea>
                </div>
                <div class="memo-group">
                    <label for="memo-tags" class="memo-label">タグ</label>
                    <input id="memo-tags" type="text" class="memo-input" name="tags" placeholder="カンマ区切りで入力">
                </div>
                <div class="nmemo-group">
                    <input type="submit" class="memo-button" value="作成">
                </div>
            </form>
        </div>
    </div>

    <script src="./assets/js/nav-test.js"></script>
</body>

</html>