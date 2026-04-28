<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">

            <div class="auth-logo">
                <span class="logo-icon">🍽️</span>
                <h1>FoodSwipe</h1>
                <p>Swipez. Savourez. Régalez-vous.</p>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="login-email" placeholder="vous@exemple.com" autocomplete="email" />
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" id="login-pwd" placeholder="••••••••" autocomplete="current-password" />
            </div>

            <p class="form-error" id="login-error">Email ou mot de passe incorrect.</p>

            <button class="btn-primary" onclick="doLogin()">Se connecter 🍴</button>

            <div class="auth-switch">
                Pas encore de compte ? <a href="/inscription">S'inscrire</a>
            </div>

        </div>
    </div>


    <script>
        function doLogin() {
            const email = document.getElementById('login-email').value.trim();
            const pwd = document.getElementById('login-pwd').value;
            const err = document.getElementById('login-error');

            if (!email || !pwd) {
                err.textContent = 'Veuillez remplir tous les champs.';
                err.classList.add('visible');
                return;
            }

            err.classList.remove('visible');

            const user = JSON.parse(localStorage.getItem('fs_user') || 'null');
            if (user && user.email === email && user.pwd === pwd) {
                localStorage.setItem('fs_logged', 'true');
                window.location.href = '/home';
            } else if (!user) {
                localStorage.setItem('fs_user', JSON.stringify({
                    name: 'Invité',
                    email,
                    pwd
                }));
                localStorage.setItem('fs_logged', 'true');
                window.location.href = '/home';
            } else {
                err.textContent = 'Email ou mot de passe incorrect.';
                err.classList.add('visible');
            }
        }

        if (localStorage.getItem('fs_logged') === 'true') {
            window.location.href = '/home';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Enter') doLogin();
        });
    </script>
</body>

</html>