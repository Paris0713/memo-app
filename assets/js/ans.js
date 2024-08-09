document.addEventListener('DOMContentLoaded', function () {
    // ログインフォームの送信イベントのリスナー
    document.querySelector('.login__form--sign-in form').addEventListener('submit', function (event) {
        event.preventDefault();

        const username = document.querySelector('#login-user').value;
        const password = document.querySelector('#login-pass').value;

        fetch('../api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: username, password: password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
            if (response.ok) {
                // ログイン成功時の処理
                window.location.href = 'memo.php'; // 例：メモページへリダイレクト
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // 登録フォームの送信イベントのリスナー
    document.querySelector('.login__form--sign-up form').addEventListener('submit', function (event) {
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
                document.querySelector('#tab-1').checked = true; // 自動的にログインタブに切り替え
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
