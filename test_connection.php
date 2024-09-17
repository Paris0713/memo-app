<?php
$servername = "localhost";
$username = "root";
$password = "root"; 

// 接続を作成
$conn = new mysqli($servername, $username, $password);

// 接続をチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
