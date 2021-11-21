<?php
/** @var array $params */

use App\models\Times;

$this->title = "Consultation";
/** @var ["id" => int, "animal" => string] $animals */
$animals = $params["animals"];

/** @var ["id" => int, "animal" => string] $typeConsultation */
$typeConsultation = $params["typeConsultation"];

/** @var ["id" => int, "animal" => string] $times */
$times = $params["times"];

$requestedConsultation = $params["requestedConsultation"];
?>

<?php if ($requestedConsultation): ?>
    {{ @component("profile_navbar") }}
<?php endif; ?>

{{ @add_style(["consultation", "profile"]) }}
<div class="container">
    <form action="" method="post">
        <div class="consultation__form">
            <div class="consultation__container">
                <h1>Réservez une consultation dans notre cabinet</h1>
                <div class="animal">
                    <label for="animal">
                        Pour quel type d'animal souhaitez-vous prendre rendez-vous ?
                    </label>
                    <select name="animal" id="animal" required>
                        <option value="null" selected disabled>Selectionner</option>
                        <?php foreach ($animals as $animal) {
                            $name = $animal["animal"];
                            $id = $animal["id"];
                            $animalSelected = ($name === $params['type_animal'] || $id === $requestedConsultation["animal"]->id) ? 'selected' : '';
                            echo "<option value='$id' $animalSelected>$name</option>";
                        } ?>
                    </select>
                    <p class='error'>{{animalError}}</p>
                </div>
                <div class="consultation">
                    <label for="type_consultation">
                        Pour quel type de consultation ?
                    </label>
                    <select name="type_consultation" id="type_consultation" required>
                        <option value="null" selected disabled>
                            Type de consultation
                        </option>
                        <?php foreach ($typeConsultation as $type) {
                            $name = $type["type"];
                            $id = $type["id"];
                            $consultationSelected = ($name === $params['consultation'] || $id === $requestedConsultation["type_consultation"]->id) ? 'selected' : '';
                            echo "<option value='$id' $consultationSelected>$name</option>";
                        } ?>
                    </select>
                    <p class='error'>{{type_consultationError}}</p>
                </div>
                <div class="problem">
                    <label for="problem">Explication du problème de votre animal</label>
                    <textarea name="problem" id="problem"><?= $requestedConsultation["problem"] ?? "" ?></textarea>
                    <p class='error'>{{problemError}}</p>
                </div>
            </div>
            <div class="right">
                <h1>Choisissez votre date</h1>
                <div class="calendar__container">
                    <p>Calendrier de prise de rendez vous</p>
                    <div class="times">
                        <select name="rdv" id="rdv" required>
                            <option value="null" selected disabled>Selectionner</option>
                            <?php if (isset($requestedConsultation["rdv"])): ?>
                                <option value='<?= Times::findOne(["id" => $requestedConsultation["rdv"]->id])->id ?>'
                                        selected><?= Times::findOne(["id" => $requestedConsultation["rdv"]->id]) ?></option>
                            <?php endif; ?>
                            
                            <?php foreach ($times as $key => $time) {
                                echo "<optgroup label='$key'>";
                                foreach ($time as $t) {
                                    $id = $t["id"];
                                    $hour = $t["hour"];
                                    echo "<option value='$id'>$hour</option>";
                                }
                                echo "</optgroup>";
                            } ?>
                        </select>
                        <p class='error'>{{rdvError}}</p>
                    </div>
                </div>
                <div class="dom__cab">
                    <p>Voulez-vous votre rendez-vous à domicile ou au cabinet?</p>
                    <div class="switch">
                        <p class="title">au cabinet</p>
                        <input type="checkbox" id="switch"
                               name="domicile" <?= isset($requestedConsultation["domicile"]) && $requestedConsultation["domicile"] === "1" ? "checked" : "" ?>/>
                        <label for="switch">Toggle</label>
                        <p class="title">à domicile</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons">
            <?php if ($requestedConsultation): ?>
                <button type="submit" class="cancel">
                    <a href="/profile/consultations">
                        Annuler
                    </a></button>
            <?php endif; ?>
            <button type="submit" class="submit">Confirmer</button>
        </div>
    </form>
</div>
{{ @component("footer") }}
