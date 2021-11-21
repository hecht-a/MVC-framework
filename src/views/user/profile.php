<?php
$this->title = "Profil";
?>
{{ @component("profile_navbar") }}

{{ @add_style("profile") }}
<div class="container">
    <div>
        <h1>Profile</h1>

        <div class="inline">
            <p>Nom: {{firstname}}</p>
            <p>Prénom: {{lastname}}</p>
        </div>
        <p>E-mail: {{email}}</p>
        <p>Téléphone: {{tel}}</p>
    </div>
</div>
{{ @component("footer") }}