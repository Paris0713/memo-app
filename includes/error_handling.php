<?php
// カスタムエラーハンドラ関数
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // エラーメッセージをログファイルに書き込む
    error_log("Error [$errno]: $errstr in $errfile on line $errline", 3, __DIR__ . '/../logs/error.log');
    // ユーザーに表示するエラーメッセージ
    echo "An error occurred. Please try again later.";
}

// カスタムエラーハンドラを設定
set_error_handler("customErrorHandler");

// 例外ハンドラ関数
function customExceptionHandler($exception) {
    // 例外メッセージをログファイルに書き込む
    error_log("Uncaught exception: " . $exception->getMessage(), 3, '../logs/error.log');
    // ユーザーに表示するエラーメッセージ
    echo "An unexpected error occurred. Please try again later.";
}

// カスタム例外ハンドラを設定
set_exception_handler("customExceptionHandler");
?>
