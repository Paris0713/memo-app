<?php
// セッションを開始
session_start();

// セッション変数をすべて解除
$_SESSION = array();

// セッションクッキーがある場合は削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを破棄
session_destroy();

// ログアウト後のリダイレクト
header("Location: ../index.php");
exit;
