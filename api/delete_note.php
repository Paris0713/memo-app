<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';


// JSONレスポンスを返すためのヘッダー設定
header('Content-Type: application/json; charset=utf-8');

// ログイン状態を確認 インクルードしているファイルから呼び出し
check_login();

$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM memos WHERE id = ? AND user_id = ?');
if ($stmt->execute([$id, $_SESSION['user_id']])) {
    // ダッシュボードページでメッセージを表示
    header('Location: ../dashboard.php?message=delete_success');
    exit();
} else {
    echo json_encode(['error' => 'メモの削除に失敗しました']);
}
exit();
?>
<!-- 
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メモ削除結果</title>
</head>
<body>
    <div id="message"></div>

    <script>
        // PHPからのJSONレスポンスを取得
        fetch('delete_note.php?id=メモのID')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('message').innerText = data.message;
                if (data.message === 'メモの削除が成功しました') {
                    // 5秒後にリダイレクト
                    setTimeout(() => {
                        window.location.href = '../dashboard.php';
                    }, 5000);
                }
            })
            .catch(error => console.error('Error:', error));
    </script>
</body>
</html> -->
