<?php
// データベース接続情報を設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'memo_app');
define('DB_USER', 'root');
define('DB_PASS', 'root');

try {
    // データベース接続を確立
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    exit();
}
?>
