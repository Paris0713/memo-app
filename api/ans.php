<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';

// JSON形式のPOSTデータを取得
$input = file_get_contents('php://input');
$data = json_decode($input, true);


// POSTメソッドであることを確認し file_get_contents('php://input') でJSONデータを取得
if (isset($data['username']) && isset($data['email']) && isset($data['password']) && isset($data['repeat_password'])) {

    
    $username = validateInput($data['username'], 'username');
    $email = validateInput($data['email'], 'email');
    $password = validateInput($data['password'], 'password');
    $repeat_password = validateInput($data['repeat_password'], 'password');

    if (!$username || !$email || !$password || !$repeat_password || $password !== $repeat_password) {
        http_response_code(400);
        echo json_encode(['message' => 'すべてのフィールドを正しく入力してください']);
        exit();
    }

    try {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = 'INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash
        ]);

        echo json_encode(['message' => 'ユーザー登録が成功しました', 'password_hash' => $password_hash]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'サーバーエラーが発生しました']);
    }
}
?>
