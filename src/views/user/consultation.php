<?php
/** @var array $params */

use App\Models\Animals;
use App\Models\Consultation;
use App\models\Times;
use App\Models\TypeConsultation;
use App\Models\User;

$this->title = "Consultation";

/** @var Consultation $consultation */
$consultation = $params["consultation"];

/** @var User $user */
$user = User::findOne(["id" => $consultation->user]);
/** @var Animals $animal */
$animal = Animals::findOne(["id" => $consultation->animal]);
/** @var TypeConsultation $typeConsultation */
$typeConsultation = TypeConsultation::findOne(["id" => $consultation->type_consultation]);
/** @var Times $rdv */
$rdv = Times::findOne(["id" => $consultation->rdv]);
?>

<div class="my__consultations">
    <h1>Consultation du <?= $rdv ?></h1>
    <div class="description">
        <div class="item">
            <p>Animal: </p>
            <p><?= $animal->type_animal ?></p>
        </div>
        <div class="item">
            <p>Type de consultation: </p>
            <p><?= $typeConsultation->consultation ?></p>
        </div>
        <div class="item">
            <p>Rendez-vous fait?: </p>
            <p><?= $consultation->done ? "✔︎" : "×" ?></p>
        </div>
    </div>
</div>
