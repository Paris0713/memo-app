<?php
require(__DIR__ . '/includes/session.php');
require(__DIR__ . '/includes/db.php');
require(__DIR__ . '/includes/validation.php');
require(__DIR__ . '/includes/error_handling.php');

// デバッグ用にPDOオブジェクトの確認
if (!isset($pdo)) {
    die('PDOオブジェクトが設定されていません');
}

// メモIDを取得
$id = $_GET['id'];

// メモをデータベースから取得
$stmt = $pdo->prepare('SELECT * FROM memos WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$memo = $stmt->fetch();

if (!$memo) {
    // メモが見つからない場合はダッシュボードにリダイレクト
    header('Location: dashboard.php');
    exit();
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
    <title><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?> - Memo App</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body>
    <div class="view">
        <div class="view-area">
            <div class="view-title">
                <h2><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
            </div>
            <div class="view-memo">
                <p><?php echo nl2br(htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
            <div class="view-button">
            <a href="dashboard.php">戻る</a>
            </div>
        </div>
    </div>
</body>

</html>