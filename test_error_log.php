<?php
ini_set('display_errors', 0); // エラーを画面に表示しない
ini_set('log_errors', 1);     // エラーをログに記録する
error_log("これはテストエラーメッセージです。");
trigger_error("これはテストの警告です。", E_USER_WARNING);
?>
