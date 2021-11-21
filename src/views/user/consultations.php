<?php
/** @var array $params */

use App\Models\Consultation;
use App\Models\TypeConsultation;

$this->title = "Mes consultations";

/** @var Consultation $consultations */
$consultations = $params["consultations"];
?>

{{ @component("profile_navbar") }}

{{ @add_style("myconsultations") }}
<div class="container">
    <div class="my__consultations">
        <h1>Liste de mes rendez-vous</h1>
        <div class="list__consultations">
            <?php /** @var Consultation $consultation */
            foreach ($consultations as $consultation): ?>
                <div class="consultation">
                    <a href="/profile/consultation/<?= $consultation->id ?>"><?= TypeConsultation::findOne(["id" => $consultation->type_consultation])->consultation ?></a>
                    <button data-consultid="<?= $consultation->id ?>" class="button edit">Modifier</button>
                    <button data-consultid="<?= $consultation->id ?>" class="button delete">Supprimer</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
{{ @component("footer") }}
