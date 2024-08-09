<?php
// データベース接続
include 'db.php';

// リクエストボディを取得
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['email']) && isset($input['password'])) {
    $email = $input['email'];
    $password = $input['password'];

    // ユーザーをデータベースから検索
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    // ユーザーが存在し、パスワードが一致するか確認
    if ($user && password_verify($password, $user['password'])) {
        
        // セッションの開始
        // ログイン成功
        session_start();
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['message' => 'ログイン成功']);
    } else {
        // ログイン失敗
        http_response_code(401);
        echo json_encode(['message' => 'メールアドレスまたはパスワードが間違っています']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => '入力データが不完全です']);
}
?>


<!-- ユーザーのログイン
 受け取るデータ: username, password
処理内容:
ユーザー名とパスワードを受け取る。
データベースからユーザー情報を取得する。
パスワードを検証する。
セッションを開始する。
返すデータ: 成功メッセージまたはエラーメッセージ -->