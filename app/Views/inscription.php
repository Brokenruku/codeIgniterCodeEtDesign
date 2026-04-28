<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FoodSwipe</title>
    <link rel="stylesheet" href="/css/style.css">   
</head>

<body>

    <div class="auth-page">
        <div class="auth-card">

            <div class="auth-logo">
                <span class="logo-icon">🥘</span>
                <h1>Créer un compte</h1>
                <p>Rejoignez la communauté des gourmands</p>
            </div>

            <div class="form-group">
                <label>Prénom &amp; Nom</label>
                <input type="text" id="reg-name" placeholder="Jean Dupont" autocomplete="name" />
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" id="reg-email" placeholder="vous@exemple.com" autocomplete="email" />
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" id="reg-pwd" placeholder="8 caractères minimum" autocomplete="new-password" />
            </div>

            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" id="reg-pwd2" placeholder="••••••••" autocomplete="new-password" />
            </div>

            <p class="form-error" id="reg-error"></p>

            <button class="btn-primary" id="register-btn" onclick="doRegister()" style="opacity: 1;">Créer mon compte 🎉</button>

            <div class="auth-switch">
                Déjà un compte ? <a href="/login">Se connecter</a>
            </div>

        </div>
    </div>

    <script>
        function doRegister() {
            const name = document.getElementById('reg-name').value.trim();
            const email = document.getElementById('reg-email').value.trim();
            const pwd = document.getElementById('reg-pwd').value;
            const pwd2 = document.getElementById('reg-pwd2').value;
            const err = document.getElementById('reg-error');
            const btn = document.getElementById('register-btn');

            if (!name || !email || !pwd || !pwd2) {
                err.textContent = 'Veuillez remplir tous les champs.';
                err.classList.add('visible');
                return;
            }
            if (pwd.length < 8) {
                err.textContent = 'Le mot de passe doit contenir au moins 8 caractères.';
                err.classList.add('visible');
                return;
            }
            if (pwd !== pwd2) {
                err.textContent = 'Les mots de passe ne correspondent pas.';
                err.classList.add('visible');
                return;
            }

            err.classList.remove('visible');
            btn.disabled = true;
            btn.textContent = 'Inscription en cours...';

            // Send registration request to server
            fetch('/inscription/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    name: name,
                    email: email,
                    password: pwd,
                    confirmPassword: pwd2
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
                    btn.textContent = 'Créer mon compte 🎉';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                err.textContent = 'Une erreur est survenue. Veuillez réessayer.';
                err.classList.add('visible');
                btn.disabled = false;
                btn.textContent = 'Créer mon compte 🎉';
            });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Enter') doRegister();
        });
    </script>

</body>
</html>