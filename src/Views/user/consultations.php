<?php
/** @var array $params */

use App\Models\Consultation;
use App\Models\TypeConsultation;

$this->title = "Mes consultations";

/** @var Consultation[] $animals */
$animals = $params["animals"];
?>

{{ @component("profile_navbar") }}
{{ @add_style("myconsultations") }}

<div class="container">
    <h1>Liste de mes Rendez-Vous</h1>
    
    <?php foreach ($animals as $animal): ?>
        <div class="items__container hidden">
            <div class="title__container">
                <p class="title"><?= $animal["animal"]->name ?></p>
                <i class="fas fa-sort-up"></i>
            </div>
            <?php foreach ($animal["consultations"] as $consultation): ?>
            <ul>
                <li>
                    <div class="item">
                        <div class="title">
                            <a href="/profile/consultation/<?= $consultation->id ?>">
                                <?= $consultation->type_consultation ?>
                            </a>
                        </div>
                        <div class="buttons">
                            <button class="button edit">Modifier</button>
                            <button class="button delete">Supprimer</button>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
{{ @component("footer") }}
