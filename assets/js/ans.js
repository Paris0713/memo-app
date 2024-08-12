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

function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('login-user').value;
    const password = document.getElementById('login-pass').value;
    const keepSignedIn = document.getElementById('check').checked;

    console.log('ログイン情報:', { username, password, keepSignedIn });
    // ここでサーバーにデータを送信する処理を行う
    // 例: fetch('/api/login.php', { method: 'POST', body: new FormData(event.target) });
}

function handleRegister(event) {
    event.preventDefault();
    
    const username = document.getElementById('register-user').value;
    const password = document.getElementById('register-pass').value;
    const repeatPassword = document.getElementById('register-repeat-pass').value;
    const email = document.getElementById('register-email').value;

    if (password !== repeatPassword) {
        console.error('パスワードが一致しません');
        return;
    }

    console.log('登録情報:', { username, password, email });
    // ここでサーバーにデータを送信する処理を行う
    // 例: fetch('/api/register.php', { method: 'POST', body: new FormData(event.target) });
}