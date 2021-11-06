<?php
/** @var array $params */

$this->title = "Consultation";
/** @var ["id" => int, "animal" => string] $animals */
$animals = $params["animals"];

/** @var ["id" => int, "animal" => string] $typeConsultation */
$typeConsultation = $params["typeConsultation"];

?>

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
                            $animalSelected = $animal["animal"] === $params['type_animal'] ? 'selected' : '';
                            $name = $animal["animal"];
                            $id = $animal["id"];
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
                            $consultationSelected = $type === $params['consultation'] ? 'selected' : '';
                            $name = $type["type"];
                            $id = $type["id"];
                            echo "<option value='$id' $consultationSelected>$name</option>";
                        } ?>
                    </select>
                    <p class='error'>{{type_consultationError}}</p>
                </div>
                <div class="problem">
                    <label for="problem">Explication du problème de votre animal</label>
                    <textarea name="problem" id="problem"></textarea>
                    <p class='error'>{{problemError}}</p>
                </div>
            </div>
            <div class="right">
                <h1>Choisissez votre date</h1>
                <div class="calendar__container">
                    <p> Calendrier de prise de rendez vous</p>
                    <div>
                        <input name="date_rdv" id="date_rdv" type="datetime-local">
                        <p class='error'>{{date_rdvError}}</p>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="submit">Confirmer</button>
    </form>
</div>