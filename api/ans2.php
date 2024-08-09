<?php
session_start();
require '../includes/db.php';
require '../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = validateInput($_POST['username'], 'username');
    $password = validateInput($_POST['password'], 'password');

    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(['message' => 'すべてのフィールドを正しく入力してください']);
        exit();
    }

    try {
        $sql = 'SELECT id, password FROM users WHERE username = :username';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['message' => 'ログイン成功']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'ユーザー名またはパスワードが間違っています']);
        }
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['message' => 'システムエラーが発生しました。後でもう一度お試しください。']);
    }
}
