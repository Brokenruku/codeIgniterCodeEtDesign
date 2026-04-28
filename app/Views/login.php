<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FoodSwipe</title>
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

            <p class="form-error" id="login-error"></p>

            <button class="btn-primary" id="login-btn" onclick="doLogin()" style="opacity: 1;">Se connecter 🍴</button>

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
            const btn = document.getElementById('login-btn');

            if (!email || !pwd) {
                err.textContent = 'Veuillez remplir tous les champs.';
                err.classList.add('visible');
                return;
            }

            err.classList.remove('visible');
            btn.disabled = true;
            btn.textContent = 'Connexion en cours...';

            // Send authentication request to server
            fetch('/login/authenticate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    email: email,
                    password: pwd
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    err.textContent = data.message;
                    err.style.color = 'green';
                    err.classList.add('visible');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    err.textContent = data.message;
                    err.classList.add('visible');
                    btn.disabled = false;
                    btn.textContent = 'Se connecter 🍴';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                err.textContent = 'Une erreur est survenue. Veuillez réessayer.';
                err.classList.add('visible');
                btn.disabled = false;
                btn.textContent = 'Se connecter 🍴';
            });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Enter') doLogin();
        });
    </script>
</body>

</html>