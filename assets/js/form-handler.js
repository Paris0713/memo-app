// データベース接続テスト
console.log('データベース接続テストを開始します');
fetch('api/db_connection_test.php')
  .then(response => {
    console.log('レスポンスステータス: ', response.status);
    console.log('レスポンスヘッダー: ', response.headers);
    return response.text();
  })
  .then(text => {
    console.log('生のレスポンス:', text);
    try {
      const data = JSON.parse(text);
      console.log('パースされたデータ:', data);
      if (data.status === 'success') {
        console.log('データベース接続成功:', data.message);
      } else {
        console.error('データベース接続エラー:', data.message);
      }
    } catch (e) {
      console.error('JSONパースエラー:', e);
      console.error('問題のあるテキスト:', text);
    }
  })
  .catch(error => {
    console.error('Fetchエラー:', error);
  });

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (loginForm) {
        console.log('ログインフォームが見つかりました');
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('ログインフォームが送信されました');

            const formData = new FormData(loginForm);
            const loginData = {};
            formData.forEach((value, key) => {
                loginData[key] = value;
            });

            console.log('ログイン情報: ', loginData);

            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(loginData)
            })
            .then(response => {
                console.log('レスポンスステータス: ', response.status);
                console.log('レスポンスヘッダー: ', response.headers);
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        throw new Error('レスポンスがJSONではありません: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('サーバーからのレスポンス: ', data);
                // ログイン成功時の処理
            })
            .catch(error => {
                console.error('エラー: ', error);
            });
        });
    }

    if (registerForm) {
        console.log('登録フォームが見つかりました');
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('登録フォームが送信されました');

            const formData = new FormData(registerForm);
            const registerData = {};
            formData.forEach((value, key) => {
                registerData[key] = value;
            });

            console.log('登録情報: ', registerData);

            fetch('register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(registerData)
            })
            .then(response => {
                console.log('レスポンスステータス: ', response.status);
                console.log('レスポンスヘッダー: ', response.headers);
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        throw new Error('レスポンスがJSONではありません: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('サーバーからのレスポンス: ', data);
                // 登録成功時の処理
            })
            .catch(error => {
                console.error('エラー: ', error);
            });
        });
    }
});

