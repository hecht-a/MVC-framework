<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/root.css'>
    <link rel='stylesheet' href='css/index.css'>
    <link rel='stylesheet' href='css/login.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title> Accueil </title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="assets/logo_chien.png" width="45" height="45"/>
                <p>Octavien Doiron</p>
            </a>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/newsletter"> Newsletter </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/consultations"> Consultations </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/avis"> Avis </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact"> Contact </a>
                    </li>
                    <li class="nav-item">
                        <a class='nav-link' href='/login'>Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

{{content}}
</body>
</html>