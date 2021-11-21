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
{{ @component("profile_navbar") }}

{{ @add_style("myconsultations") }}
<div class="container">
    <div class="my__consultations">
        <div class="title">
            <a href="/profile/consultations"><button>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>Retour
                </button></a>
            <h1>Consultation du <?= $rdv ?></h1>
        </div>
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
</div>
{{ @component("footer") }}
