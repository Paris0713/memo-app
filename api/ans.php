<?php
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
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

        echo json_encode(['message' => 'ユーザー登録が成功しました']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'サーバーエラーが発生しました']);
    }
}
?>
