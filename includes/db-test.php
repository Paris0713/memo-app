<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
// ... (残りのコードは同じ)

$servername = "localhost";
$username = "root";
$password = "root";  // 実際のパスワードに置き換えてください
$dbname = "memo_app";    // 実際のデータベース名に置き換えてください

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// 接続をチェック
if ($conn->connect_error) {
    $response = array("status" => "error", "message" => "データベース接続に失敗しました: " . $conn->connect_error);
} else {
    $response = array("status" => "success", "message" => "データベース接続に成功しました");
}

// JSON形式でレスポンスを返す
echo json_encode($response);

file_put_contents('output.log', ob_get_contents());
?>
