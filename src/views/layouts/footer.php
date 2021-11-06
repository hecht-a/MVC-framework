<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='/css/index.css'>
    <link rel='stylesheet' href='/css/login.css'>
    <link rel='stylesheet' href='/css/burger.css'>
    <link rel='stylesheet' href='/css/profile.css'>
    <link rel='stylesheet' href='/css/footer.css'>
    <script src="/js/index.js" defer></script>
    <title>{{title}}</title>
</head>
<body>
<header>
    <a class="brand" href="/">
        <img src="/assets/logo_chien.png" width="45" height="45"/>
        <p>Octavien Doiron</p>
    </a>
    <div>
        <div class="bar">
            <img src="/assets/burger.svg" alt="">
        </div>
        <nav class="menu-collapsed">
            <ul>
                <li><a href="/newsletter">newsletter</a></li>
                <li><a href="/avis">avis</a></li>
                <li><a href="/consultation">consultation</a></li>
                <li><a href="/contact">contact</a></li>
                {{user}}
            </ul>
        </nav>
    </div>
</header>

{{content}}
<footer>
    <div class="left">
        <div class="logo__container">
            <div class="logo">
                <img src="/assets/logo_chien.png" width="80" height="80" alt="">
            </div>
            <div class="text">
                <p>Octavien Doiron</p>
                <p>Clinique Vétérinaire</p>
            </div>
        </div>
        <p class="slogan">Ensemble, pour une médecine d'excellence</p>
    </div>
    <div class="sep"></div>
    <div class="right">
        <div class="link">
            <a href="/login">Nous rejoindre</a>
            <a href="/contact">Nous contacter </a>
        </div>
        <div class="link">
            <a>Mentions légales</a>
            <a>Politique des cookies</a>
            <a>Plan du site</a>
            <a>Crédits</a>
        </div>
    </div>
</footer>
</body>
</html>