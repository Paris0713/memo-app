<?php
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';

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
    <title><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?> - Memo App</title>
    <link rel="stylesheet" href="./css/view_memo.css">
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8')); ?></p>
        <a href="dashboard.php">戻る</a>
    </div>
</body>

</html>
