document.addEventListener('DOMContentLoaded', function () {
    // ログインフォームの送信イベントのリスナー(DOMが完全に構築された後にスクリプトを実行)
    
    // login__form--sign-inクラスのform要素を選択して
    // 送信されたときのイベントハンドラを追加
    document.querySelector('.login__form--sign-in form').addEventListener('submit', function (event) {
        // ページのリロードを防止
        event.preventDefault();

        // ユーザー名とパスワードの入力値を取得
        const username = document.querySelector('#login-user').value;
        const password = document.querySelector('#login-pass').value;

        // fetch関数を使用して、../api/login.phpにPOSTリクエストを送信
        fetch('../api/login.php', {
            method: 'POST',
            // リクエストヘッダーを設定 json形式
            headers: {
                'Content-Type': 'application/json'
            },
            // リクエストボディをJSON形式に変換して送信
            body: JSON.stringify({ username: username, password: password })
        })

        // レスポンスデータをJSON形式に変換
        .then(response => response.json())
        .then(data => {
            // サーバーからのメッセージがあればアラートで表示
            if (data.message) {
                alert(data.message);
            }
            if (response.ok) {
                // ログイン成功時の処理 dashboard.phpへ移動
                window.location.href = 'dashboard.php';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // 登録フォームの送信イベントのリスナー

    // フォームの選択と送信イベントの追加
    document.querySelector('.login__form--sign-up form').addEventListener('submit', function(event) {
        event.preventDefault();

        const username = document.querySelector('#register-user').value;
        const email = document.querySelector('#register-email').value;
        const password = document.querySelector('#register-pass').value;
        const repeat_password = document.querySelector('#register-repeat-pass').value;

        fetch('../api/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: username, email: email, password: password, repeat_password: repeat_password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
            if (response.ok) {
                // 登録成功時の処理
                // 自動的にログインタブに切り替え
                document.querySelector('#tab-1').checked = true;
            }
        })
        .catch(error => console.error('Error:', error));
    });
});