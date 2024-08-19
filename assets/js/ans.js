document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.login__form--sign-in form');
    const registerForm = document.querySelector('.login__form--sign-up form');

    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
});

async function handleLogin(event) {
    event.preventDefault();
    
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
        const response = await fetch('/lesson/memo-app/api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(loginData)
        });

        if (response.ok) {
            const data = await response.json();
            console.log('ログイン成功:', data);
            window.location.href = '/dashboard.html'; // ログイン成功後のリダイレクト先
        } else {
            const errorData = await response.json();
            console.error('ログイン失敗:', errorData);
        }
    } catch (error) {
        console.error('エラー:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('.login__form--sign-up form');

    if (registerForm) {
        console.log('登録フォームが見つかりました');
        registerForm.addEventListener('submit', handleRegister);
    } else {
        console.log('登録フォームが見つかりませんでした');
    }
});


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

        if (response.ok) {
            const data = await response.json();
            console.log('登録成功:', data);
            console.log('ハッシュ化されたパスワード:', data.password_hash);
            window.location.href = '/lesson/memo-app/success-page.html'; // 登録成功後のリダイレクト先
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
}
