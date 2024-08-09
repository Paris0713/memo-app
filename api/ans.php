<?php
session_start();
require '../includes/db.php';
require '../includes/validation.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('POSTリクエストを受信しました。');

    // 送信されたデータを確認
    print_r($_POST);
        
    $username = validateInput($_POST['username'], 'username');
    $email = validateInput($_POST['email'], 'email');
    $password = validateInput($_POST['password'], 'password');
    $repeat_password = validateInput($_POST['repeat_password'], 'password');

    if (!$username || !$email || !$password || !$repeat_password) {
        http_response_code(400);
        echo json_encode(['message' => 'すべてのフィールドを正しく入力してください']);
        exit();
    }
     // パスワードが一致するか確認
    if ($password !== $repeat_password) {
        http_response_code(400);
        echo json_encode(['message' => 'パスワードが一致しません']);
        exit();
    }
    // パスワードをハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    echo 'ハッシュ化されたパスワード: ' . $hashed_password;


    try {
        $sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);
        echo json_encode(['message' => 'ユーザー登録が成功しました']);
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['message' => 'システムエラーが発生しました。後でもう一度お試しください。']);
    }
}

// 新規ユーザーの登録
//  受け取るデータ: username, password, email
// 処理内容:
// ユーザー名とパスワードを受け取る。
// パスワードをハッシュ化する。
// データベースにユーザー情報を保存する。
// 返すデータ: 成功メッセージまたはエラーメッセージ

// なぜ json_encode を使うのか？
//  json_encode は以下のようなシナリオで便利：

// APIレスポンス: サーバーサイドのPHPスクリプトからクライアントサイド（例えば、JavaScript）にデータを送るときに使用
// JSON形式はJavaScriptで簡単に扱えるため、フロントエンドとバックエンド間のデータ交換に適して
// データ保存: データベースに複雑なデータ構造を保存する際に、JSON形式に変換して保存する
// デバッグ: 配列やオブジェクトの内容を簡単に確認するためにJSON形式に変換して表示することができる