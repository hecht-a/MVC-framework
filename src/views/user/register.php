<div class="container">
    <div class="form">
        <div class="form__container">
            <div class="icon"><img src="assets/compte.png" alt=""></div>
            <div class="message">Inscription</div>
            <div class="formulaire">
                <form name="login" action="" method="POST">
                    <input type="text" name="lastname" placeholder="Nom" value="{{lastname}}">
                    <p class='error'>{{lastnameError}}</p>
                    <input type="text" name="firstname" placeholder="Prénom" value="{{firstname}}">
                    <p class='error'>{{firstnameError}}</p>
                    <input type="email" name="email" placeholder="E-mail" value="{{email}}">
                    <p class='error'>{{emailError}}</p>
                    <input type="password" name="password" placeholder="Mot de passe">
                    <p class='error'>{{passwordError}}</p>
                    <input type="tel" name="tel" pattern="[0-9]{10}" placeholder="Téléphone" value="{{tel}}">
                    <p class='error'>{{telError}}</p>
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