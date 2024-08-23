<?php
require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestPayload = file_get_contents("php://input");
    $data = json_decode($requestPayload, true);

    $username = validateInput($data['username'], 'username');
    $password = validateInput($data['password'], 'password');

    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(['message' => 'すべてのフィールドを正しく入力してください']);
        exit();
    }

    try {
        $sql = 'SELECT id, username, password_hash FROM users WHERE username = :username';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            error_log('User not found: ' . $username);
        }

        if ($user && password_verify($password, $user['password_hash'])) {
            // ユーザーidをセッションに保存
            $_SESSION['user_id'] = $user['id'];
            // ユーザー名をセッションに保存
            $_SESSION['username'] = $user['username'];
            echo json_encode(['message' => 'ログイン成功']);
            exit();
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
