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

    try {
        const response = await fetch('http://localhost/lesson/memo-app/api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(loginData)
        });
    
        console.log('レスポンスステータス:', response.status);
        console.log('レスポンスヘッダー:', response.headers);
    
        const responseBody = await response.text(); // レスポンスをテキストとして取得
        console.log('レスポンスボディ:', responseBody);
    
        if (response.ok) {
            try {
                const data = JSON.parse(responseBody); // テキストをJSONとして解析
                console.log('ログイン成功:', data);
                window.location.href = '/lesson/memo-app/dashboard.php'; // ログイン成功後のリダイレクト先
            } catch (jsonError) {
                console.error('JSON解析エラー:', jsonError);
            }
        } else {
            console.error('ログイン失敗:', responseBody);
        }
    } catch (error) {
        console.error('エラー:', error);
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

    try {
        const response = await fetch('/lesson/memo-app/api/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(registerData)
        });

        // console.log('レスポンスステータス:', response.status);
        // console.log('レスポンスヘッダー:', response.headers);

        if (response.ok) {
            const data = await response.json();
            console.log('登録成功:', data);
            console.log('ハッシュ化されたパスワード:', data.password_hash);
            window.location.href = '/lesson/memo-app/dashboard.php'; // 登録成功後のリダイレクト先
        } else {
            const errorData = await response.json();
            console.error('登録失敗:', errorData);
            console.log('レスポンスステータス:', response.status);
            console.log('レスポンスヘッダー:', response.headers);
            console.log('レスポンスボディ:', await response.text()); // デバッグ用にレスポンスボディをテキストで表示
        }
    } catch (error) {
        console.error('エラー:', error);
    }
}}
