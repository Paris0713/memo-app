<?php
require 'includes/session.php';
require 'includes/db.php';
require 'includes/validation.php';



// グローバル変数として$pdoを宣言
global $pdo;

// ログインしているか確認
 if (!isset($_SESSION['user_id'])) {
     header('Location: index.php');
     exit();
 }

// ユーザーのメモを取得
$stmt = $pdo->prepare('SELECT * FROM memos WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$memos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Memo App</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>

<body>
    <div class="container">
        <h1>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</h1>
        <h2>あなたのメモ</h2>
        <ul>
            <?php foreach ($memos as $memo): ?>
                <li>
                    <!-- 作成したメモのリンク -->
                    <h3><a href="view_memo.php?id=<?php echo $memo['id']; ?>"><?php echo htmlspecialchars($memo['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                    <p><?php echo nl2br(htmlspecialchars($memo['content'], ENT_QUOTES, 'UTF-8')); ?></p>
                    <!-- メモの編集・削除リンク -->
                    <a href="edit_memo.php?id=<?php echo $memo['id']; ?>">編集</a>
                    <a href="delete_memo.php?id=<?php echo $memo['id']; ?>">削除</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2>新しいメモを作成</h2>
        <form action="create_memo.php" method="post">
            <div class="group">
                <label for="memo-title" class="label">タイトル</label>
                <input id="memo-title" type="text" class="input" name="title" required>
            </div>
            <div class="group">
                <label for="memo-content" class="label">内容</label>
                <textarea id="memo-content" class="input" name="content" required></textarea>
            </div>
            <div class="group">
                <input type="submit" class="button" value="作成">
            </div>
        </form>
    </div>
</body>

</html>



<!-- 
 メモの一覧表示：ユーザーが作成したメモの一覧を表示
新規メモの作成：新しいメモを作成するためのフォーム
メモの編集・削除：既存のメモを編集または削除する機能
タグの管理：メモにタグを付ける機能を提供し、タグの作成・編集・削除
 -->

