<?php
// 必要なファイルのインクルード
require(__DIR__ . '/../includes/session.php');
require(__DIR__ . '/../includes/db.php');
require(__DIR__ . '/../includes/validation.php');
require(__DIR__ . '/../includes/error_handling.php');



// JSONレスポンスを返すためのヘッダー設定
header('Content-Type: application/json');

// $_SERVER['REQUEST_METHOD']がPOSTかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // リクエストボディの取得とデコード
    // file_get_contents("php://input")でリクエストボディを取得
    $requestPayload = file_get_contents("php://input");
    // json_decodeでJSONデータを連想配列に変換    
    $data = json_decode($requestPayload, true);

    // validateInput関数を使って、ユーザー名とパスワードが正しく入力されているかを確認
    $username = validateInput($data['username'], 'username');
    $password = validateInput($data['password'], 'password');

    // バリデーションエラーのチェック
    // バリデーションに失敗した場合、HTTPステータスコード400（Bad Request）を返し、
    // エラーメッセージをJSON形式で出力
    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(['message' => 'すべてのフィールドを正しく入力してください']);
        exit();
    }

    try {
        // データベースからユーザー情報を取得
        // ユーザー名を使ってデータベースからユーザー情報を取得
        $sql = 'SELECT id, username, password_hash FROM users WHERE username = :username';
        // $pdoオブジェクトのprepareメソッドを使用して、SQLステートメントを準備
        $stmt = $pdo->prepare($sql);
        // executeメソッドを呼び出して、ステートメントを実行
        $stmt->execute(['username' => $username]);
        // fetchメソッドを使用して、SQLクエリの結果を取得
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ユーザーが存在しない場合のエラーログ
        if ($user === false) {
            error_log('User not found: ' . $username);
        }

        // パスワードの検証
        if ($user && password_verify($password, $user['password_hash'])) {
            // ユーザーidをセッションに保存
            $_SESSION['user_id'] = $user['id'];
            // ユーザー名をセッションに保存
            $_SESSION['username'] = $user['username'];
            echo json_encode(['message' => 'ログイン成功']);
            exit();
        } else {
            // 一致しない場合、HTTPステータスコード401（Unauthorized）を返し
            // エラーメッセージをJSON形式で出力
            http_response_code(401);
            echo json_encode(['message' => 'ユーザー名またはパスワードが間違っています']);
        }


    } catch (PDOException $e) {
        // データベースエラーが発生した場合、エラーログを記録し
        // HTTPステータスコード500（Internal Server Error）を返す
        error_log('Database error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['message' => 'システムエラーが発生しました。後でもう一度お試しください。']);
    }
}
