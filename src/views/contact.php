<?php
$this->title = "Nous contacter";
?>

{{ @add_style("login") }}
<div class="container">
    <div class="form">
        <div class="form__container">
            <div class="icon"><img src="/assets/contact.svg" alt=""></div>
            <p class="message">Nous contacter</p>
            <div class="formulaire">
                <form name="login" action="" method="POST">
                    <input type="email" name="email" placeholder="E-mail">
                    <p class='error'>{{emailError}}</p>
                    <input type="text" name="subject" placeholder="Sujet">
                    <p class='error'>{{subjectError}}</p>
                    <textarea name="content" placeholder="Contenu"></textarea>
                    <p class='error'>{{contentError}}</p>
                    <button type="submit" class="submit">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{ @component("footer") }}
