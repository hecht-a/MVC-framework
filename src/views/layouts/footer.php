<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/index.css'>
    <link rel='stylesheet' href='css/login.css'>
    <link rel='stylesheet' href='css/burger.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous">
    <script src="js/index.js" defer></script>
    <title> Accueil </title>
</head>
<body>
<header>
    <a class="brand" href="/">
        <img src="assets/logo_chien.png" width="45" height="45"/>
        <p>Octavien Doiron</p>
    </a>
    <div>
        <div class="bar">
            <img src="assets/burger.svg" alt="">
        </div>
        <nav class="menu-collapsed">
            <ul>
                <li><a href="/newsletter">newsletter</a></li>
                <li><a href="/avis">avis</a></li>
                <li><a href="/contact">contact</a></li>
                <li><a href="/login">connexion</a></li>
            </ul>
        </nav>
    </div>
</header>

{{content}}
<footer>
    <div class="d-flex flex-row justify-content-center">
        <div id="left" class="d-flex flex-column align-item-center">
            <div id="footer_logo" class="d-flex flex-row">
                <div>
                    <div class="logo"><img src="assets/logo_chien.png" width="80" height="80"> </a></div>
                </div>
                <div class="d-flex flex-column">
                    <div class="d-flex"> Octavien</div>
                    <div class="d-flex justify-content-center"> Doiron</div>
                    <div class="clinique"> Clinique Vétérinaire</div>
                </div>
            </div>
            <div class="slogan"> Ensemble, pour une médecine d'excellence</div>
        </div>
        <div class="middle">
            <div id="middle_content" class="d-flex flex-column m-2 align-self-center">
                <li>
                    <a> Nous rejoindre </a>
                </li>
                <li>
                    <a> Nous contacter </a>
                </li>
                <li>
                    <a> FAQ </a>
                </li>
                <li>
                    <a> Presse </a>
                </li>
                <li>
                    <a> Partenaires </a>
                </li>
                <li>
                    <a> Actualités </a>
                </li>
            </div>
            <div id="middle_content" class="d-flex flex-column m-2">
                <li>
                    <a> Mentions légales </a>
                </li>
                <li>
                    <a> Politique d'utilisation des données </a>
                </li>
                <li>
                    <a> Politique des cookies </a>
                </li>
                <li>
                    <a> Plan du site </a>
                </li>
                <li>
                    <a> Crédits </a>
                </li>
            </div>
        </div>
        <div class="d-flex flex-column  m-2">
            Retrouvez-nous sur les réseaux sociaux
            <div class="d-flex flex-row">
                <li>
                    <a><img src="assets/facebook.png" width="20" height="20"> </a>
                </li>
                <li>
                    <a><img src="assets/twitter.png" width="20" height="20"> </a>
                </li>
                <li>
                    <a><img src="assets/instagram.png" width="20" height="20"> </a>
                </li>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
<style>
    footer {
        background-color: rgb(117, 117, 117);
        position: absolute;
        bottom: 0;
        width: 100%;
        color: white;
        font-size: 12px;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .middle {
        display: flex;
    }

    #middle_content {
        vertical-align: middle;
    }

    .clinique {
        font-size: 12px;
    }

    #left {
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 20px;
    }

    .slogan {
        width: 250px;
        text-align: center;
        font-size: 14px;
        margin-top: 10px;
    }


    #footer_logo:before {
        width: 250px;
        border-bottom: solid 2px white;
        content: '';
        position: absolute;
        margin-top: 87px;
    }


    li {
        list-style-type: none;
    }
</style>
