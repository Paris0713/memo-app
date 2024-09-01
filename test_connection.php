<?php
$servername = "localhost";
$username = "root";
$password = "root"; // 初期設定では通常パスワードは空ですが、設定に合わせて変更してください

// 接続を作成
$conn = new mysqli($servername, $username, $password);

// 接続をチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
