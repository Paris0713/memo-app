<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';
require '../includes/error_handling.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'ログインしてください']);
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE FROM memos WHERE id = ? AND user_id = ?');
if ($stmt->execute([$id, $_SESSION['user_id']])) {
    echo json_encode(['message' => 'メモの削除が成功しました']);
} else {
    echo json_encode(['error' => 'メモの削除に失敗しました']);
}
exit();
?>

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
</html>
