<?php
$this->title = "Inscription";
?>

{{ @add_style("register") }}
<div class="container">
    <div class="form">
        <div class="form__container">
            <div class="icon"><img src="/assets/compte.png" alt=""></div>
            <div class="message">Inscription</div>
            <div class="formulaire">
                <form name="login" action="" method="POST">
                    <div class="inputs">
                        <div class="left">
                            <input type="text" name="lastname" placeholder="Nom" value="{{lastname}}">
                            <p class='error error__margin'>{{lastnameError}}</p>
                            <input type="text" name="firstname" placeholder="Prénom" value="{{firstname}}">
                            <p class='error error__margin'>{{firstnameError}}</p>
                            <input type="email" name="email" placeholder="E-mail" value="{{email}}">
                            <p class='error error__margin'>{{emailError}}</p>
                            <input type="password" name="password" placeholder="Mot de passe">
                            <p class='error error__margin'>{{passwordError}}</p>
                            <input type="tel" name="tel" pattern="[0-9]{10}" placeholder="Téléphone" value="{{tel}}">
                            <p class='error error__margin'>{{telError}}</p>
                        </div>
                        <div class="right">
                            <input type="text" name="address" placeholder="Adresse">
                            <p class='error error__margin'>{{addressError}}</p>
                            <input type="text" name="city" placeholder="Ville">
                            <p class='error error__margin'>{{cityError}}</p>
                            <input type="number" name="post_code" placeholder="Code postal">
                            <p class='error error__margin'>{{post_codeError}}</p>
                        </div>
                    </div>
                    <button type="submit" class="submit">S'inscrire</button>
                </form>
            </div>
            <div class="separateur">
                <p>OU</p>
            </div>
            <div class="link__signin">
                <a href="/login">Se connecter</a>
            </div>
        </div>
    </div>
</div>
{{ @component("footer") }}