document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('#form-login');
    const registerForm = document.querySelector('#form-register');

    // フォームの存在を確認し、ログを出力
    if (loginForm) {
        console.log('ログインフォームが見つかりました');
        // ログインフォームのイベントリスナーを設定
        loginForm.addEventListener('submit', handleLogin);
    } else {
        console.log('ログインフォームが見つかりませんでした');
    }

    if (registerForm) {
        console.log('登録フォームが見つかりました');
        // 登録フォームのイベントリスナーを設定
        registerForm.addEventListener('submit', handleRegister);
    } else {
        console.log('登録フォームが見つかりませんでした');
    }
});

async function handleLogin(event) {
    event.preventDefault();
    console.log('ログインフォームが送信されました');
    
    const username = document.getElementById('login-user').value;
    const password = document.getElementById('login-pass').value;
    const keepSignedIn = document.getElementById('check').checked;

    const loginData = {
        username: username,
        password: password,
        keepSignedIn: keepSignedIn
    };

    console.log('ログイン情報:', loginData);
    // デバッグ用
    console.log('送信するログインデータ:', JSON.stringify(loginData));

    try {
        const response = await fetch('../api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(loginData)
        });

        console.log('レスポンスステータス:', response.status);
        console.log('レスポンスヘッダー:', response.headers);

        const responseData = await response.json();
        if (response.ok) {
            console.log('ログイン成功:', responseData);
            window.location.href = '../dashboard.php'; // ログイン成功後のリダイレクト先
        } else {
            console.error('ログイン失敗:', responseData);
        }
    } catch (error) {
        console.error('エラー:', error);
    }
}

async function handleRegister(event) {
    event.preventDefault();
    console.log('登録フォームが送信されました');

    const username = document.getElementById('register-user').value;
    const password = document.getElementById('register-pass').value;
    const repeatPassword = document.getElementById('register-repeat-pass').value;
    const email = document.getElementById('register-email').value;

    if (password !== repeatPassword) {
        console.error('パスワードが一致しません');
        return;
    }

    const registerData = {
        username: username,
        password: password,
        repeat_password: repeatPassword,
        email: email
    };

    console.log('送信するデータ:', registerData);
    // デバッグ用
    console.log('送信する登録データ:', JSON.stringify(registerData));

    try {
        const response = await fetch('../api/register.php', {
            method: 'POST',
            // リクエストのヘッダーでJSON形式を設定
            headers: {
                'Content-Type': 'application/json'
            },
            // JSON形式に変換
            body: JSON.stringify(registerData)
        });

        // テキストとしてレスポンスを取得
        const responseText = await response. text();
        console.log('レスポンス:', responseText);

        // JSON形式に変換
        const responseData = JSON.parse(responseText);

        // const responseData = await response.json();
        if (response.ok) {
            console.log('登録成功:', responseData);
            // console.log('ハッシュ化されたパスワード:', responseData.password_hash);
            window.location.href = '../dashboard.php'; // 登録成功後のリダイレクト先
        } else {
            console.error('登録失敗:', responseData);
        }
    } catch (error) {
        console.error('エラー:', error);
    }
}
