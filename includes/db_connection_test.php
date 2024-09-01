<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lesson/memo-app/includes/error_handling.php';
// エラー報告を無効にし、ログに記録する
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/lesson/memo-app/logs/php_error.log');  // memo-appのlogsフォルダを使用

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "root";  // 実際のパスワードに置き換えてください
$dbname = "memo_app";    // 実際のデータベース名に置き換えてください

// データベース接続
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("データベース接続に失敗しました: " . $conn->connect_error);
    }
    
    $response = array("status" => "success", "message" => "データベース接続に成功しました");
} catch (Exception $e) {
    $response = array("status" => "error", "message" => $e->getMessage());
    error_log($e->getMessage());  // エラーをログに記録
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    if (isset($conn)) {
        $conn->close();
    }
}
?>