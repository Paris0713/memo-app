<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDGothic:wght@400;700&family=Cactus+Classical+Serif&family=IBM+Plex+Sans+JP:wght@100;200;300;400;500;600;700&family=M+PLUS+Rounded+1c:wght@100;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <title>Memo App</title>
    <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <script src="./assets/js/main.js" defer></script>

    <!-- git -->
</head>

<body>
    <div class="container">
        <div class="login-wrap">
            <div class="login-php">

                <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
                <label for="tab-1" class="tab">Sign In</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up">
                <label for="tab-2" class="tab">Sign Up</label>
                <div class="login-form login__form">
                    <div class="sign-in-htm login__form--sign-in">
                        <form id="form-login" action="./api/login.php" method="post">
                            <div class="group">
                                <label for="login-user" class="label">Username</label>
                                <!-- 警告が出るのでautocomplete属性を追加 -->
                                <input id="login-user" type="text" class="input" name="username" required autocomplete="username">
                            </div>
                            <div class="group">
                                <label for="login-pass" class="label">Password</label>
                                <input id="login-pass" type="password" class="input" name="password" data-type="password" required autocomplete="current-password">
                            </div>
                            <div class="group">
                                <input id="check" type="checkbox" class="check" checked>
                                <label for="check"><span class="icon"></span> Keep me Signed in</label>
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Sign In">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <a href="#forgot">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                    <div class="sign-up-htm login__form--sign-up">
                        <form id="form-register" action="./api/register.php" method="post">
                            <div class="group">
                                <label for="register-user" class="label">Username</label>
                                <input id="register-user" type="text" class="input" name="username" required autocomplete="username">
                            </div>
                            <div class="group">
                                <label for="register-pass" class="label">Password</label>
                                <input id="register-pass" type="password" class="input" name="password" data-type="password" required autocomplete="new-password">
                            </div>
                            <div class="group">
                                <label for="register-repeat-pass" class="label">Repeat Password</label>
                                <input id="register-repeat-pass" type="password" class="input" name="repeat_password" data-type="password" required autocomplete="new-password">
                            </div>
                            <div class="group">
                                <label for="register-email" class="label">Email Address</label>
                                <input id="register-email" type="email" class="input" name="email" required autocomplete="email">
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Sign Up">
                            </div>
                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <label for="tab-1">Already Member?</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>