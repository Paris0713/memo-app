<?php
// 入力データのサニタイズ
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// リダイレクト処理
function redirect($url) {
    header("Location: $url");
    exit();
}

// 日付フォーマット
function formatDate($date) {
    return date("Y-m-d H:i:s", strtotime($date));
}
?>
