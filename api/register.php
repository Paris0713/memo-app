<?php
// エラーレポート設定
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// エラーログをファイルに記録
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/log/php_errors.log');

// 必要なファイルのインクルード
require(__DIR__ . '/../includes/session.php');
require(__DIR__ . '/../includes/db.php');
require(__DIR__ . '/../includes/validation.php');
require(__DIR__ . '/../includes/error_handling.php');

// JSONレスポンスを返すためのヘッダー設定
header('Content-Type: application/json; charset=utf-8'); 

// デバッグ関数
function debug_log($message) {
    error_log(print_r($message, true));
}

// POSTリクエストの処理  クエストメソッドがPOSTかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_log('POSTリクエストを受信しました');  

    $rawData = file_get_contents('php://input');
    debug_log('受信した生のデータ: ' . $rawData);

    $data = json_decode($rawData, true);

    if ($data === null) {
        error_log('JSONデコードエラー: ' . json_last_error_msg());
        echo json_encode(["message" => "無効なJSONデータです", "error" => json_last_error_msg()]);
        exit;
    }

    debug_log('デコードされたデータ: ' . print_r($data, true));
    
    // 各入力フィールドが存在するか確認
    if (isset($data['username']) && isset($data['email']) && isset($data['password']) && isset($data['repeat_password'])) {
        $username = validateInput($data['username'], 'username');
        $email = validateInput($data['email'], 'email');
        $password = validateInput($data['password'], 'password');
        $repeat_password = validateInput($data['repeat_password'], 'password');

        debug_log('バリデーション結果: ' . print_r([$username, $email, $password, $repeat_password], true));

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
            // トランザクションの開始
            $pdo->beginTransaction();
        
            // パスワードのハッシュ化
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            // データベースに新しいユーザーを挿入
            $sql = 'INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)';
            $stmt = $pdo->prepare($sql);
            
            // 実行結果を$resultに格納
            $result = $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash
            ]);
        
            // トランザクションのコミット
            $pdo->commit();
        
            debug_log('SQLクエリの実行結果: ' . ($result ? 'success' : 'failure'));
        
            if ($result) {
                echo json_encode(['message' => 'ユーザー登録が成功しました', 'password_hash' => $password_hash], JSON_UNESCAPED_UNICODE);
            } else {
                debug_log('SQLエラー: ' . print_r($stmt->errorInfo(), true));
                http_response_code(500);
                echo json_encode(['message' => 'データベース操作に失敗しました'], JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            // トランザクションのロールバック
            $pdo->rollBack();
            debug_log('PDOException: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['message' => 'サーバーエラーが発生しました', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
        
    } else {
        debug_log('必須フィールドが不足しています');
        http_response_code(400);
        echo json_encode(array("message" => "すべてのフィールドを正しく入力してください"), JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    debug_log('無効なリクエストメソッド: ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(array("message" => "無効なリクエスト方法です"), JSON_UNESCAPED_UNICODE);
    exit;
}
