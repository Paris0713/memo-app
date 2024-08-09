<?php
// validateInput() 関数は、入力データをバリデーションするための汎用関数
// ($data, $type) は関数の引数
function validateInput($data, $type) {
    // switch ($type) は、渡された $type の値に基づいて異なる処理を行うための構造
    switch ($type) {
        // ユーザーネームをエスケープ処理
        case 'username':
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');    
        // パスワードもエスケープ   
        case 'password':
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); 
        case 'email':
            return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : null;
        default:
            return null;
    }
}

// $data: バリデーション対象のデータ。
// $type: バリデーションの種類を指定する文字列。
// case 'username': は、$type が 'username' の場合に実行される部分
// htmlspecialchars は、特殊文字をHTMLエンティティに変換するための関数で、XSS攻撃を防ぐため
// filter_var($data, FILTER_VALIDATE_EMAIL) は、入力データが有効なメールアドレス形式かを検証
// ? $data : null は、メールアドレスが有効な場合はそのまま $data を返し、無効な場合は null を返す


// filter_var($data, FILTER_SANITIZE_STRING) VScodeでが非推奨のため
// htmlspecialchars($data, ENT_QUOTES, 'UTF-8') に置き換え