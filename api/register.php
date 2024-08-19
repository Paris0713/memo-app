<?php

require '../includes/session.php';
require '../includes/db.php';
require '../includes/validation.php'; // validation.php をインクルード

header('Content-Type: application/json; charset=utf-8'); // ヘッダーを追加

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON形式のPOSTデータを取得
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data === null) {
        // JSONデコードに失敗した場合のエラーメッセージを出力
        echo json_encode(["message" => "無効なJSONデータです", "error" => json_last_error_msg()]);
        exit;
    }


    if (isset($data['username']) && isset($data['email']) && isset($data['password']) && isset($data['repeat_password'])) {
        $username = validateInput($data['username'], 'username');
        $email = validateInput($data['email'], 'email');
        $password = validateInput($data['password'], 'password');
        $repeat_password = validateInput($data['repeat_password'], 'password');

        // 入力の検証
        if (!$username) {
            http_response_code(400);
            echo json_encode(['message' => 'ユーザー名を正しく入力してください'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        if (!$email) {
            http_response_code(400);
            echo json_encode(['message' => 'メールアドレスを正しく入力してください'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        if (!$password) {
            http_response_code(400);
            echo json_encode(['message' => 'パスワードを正しく入力してください'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        if (!$repeat_password) {
            http_response_code(400);
            echo json_encode(['message' => '確認用パスワードを正しく入力してください'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        if ($password !== $repeat_password) {
            http_response_code(400);
            echo json_encode(['message' => 'パスワードが一致しません'], JSON_UNESCAPED_UNICODE);
            exit();
        }

        try {
            // パスワードのハッシュ化
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = 'INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash
            ]);

            echo json_encode(['message' => 'ユーザー登録が成功しました', 'password_hash' => $password_hash], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'サーバーエラーが発生しました'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        // エラーメッセージを返す
        http_response_code(400);
        echo json_encode(array("message" => "すべてのフィールドを正しく入力してください"), JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    // POSTメソッド以外のリクエストに対する処理
    http_response_code(405);
    echo json_encode(array("message" => "無効なリクエスト方法です"), JSON_UNESCAPED_UNICODE);
    exit;
}
