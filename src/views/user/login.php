<?php
$this->title = "Connexion";
?>

{{ @add_style("login") }}
<div class="container">
    <div class="form">
        <div class="form__container">
            <div class="icon"><img src="/assets/compte.png" alt=""></div>
            <p class="message">Connexion</p>
            <div class="formulaire">
                <form name="login" action="" method="POST">
                    <input type="email" name="email" placeholder="E-mail">
                    <p class='error error__margin'>{{emailError}}</p>
                    <input type="password" name="password" placeholder="Mot de passe">
                    <p class='error error__margin'>{{passwordError}}</p>
                    <button type="submit" class="submit">Se connecter</button>
                </form>
            </div>
            <div class="separateur">
                <p>OU</p>
            </div>
            <div class="link__signin">
                <a href="/register">S'inscrire</a>
            </div>
        </div>
    </div>
</div>
{{ @component("footer") }}
