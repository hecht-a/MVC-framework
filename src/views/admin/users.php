<?php
/** @var Array $params */

use App\Models\User;

$this->title = "Utilisateurs";
$users = User::findAll();

?>

<h1>users</h1>

<div class="table">
    <table>
        <thead>
        <tr>
            <th>Id</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Téléphone</th>
            <th>Admin</th>
        </tr>
        </thead>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->lastname ?></td>
                <td><?= $user->firstname ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->tel ?></td>
                <td>
                    <form class="admin__user" method="post">
                        <input type="hidden" name="user_<?= $user->id ?>"
                               value="<?= $user->admin ? "false" : "true" ?>">
                        <button class="<?= (bool)$user->admin ? "admin" : "" ?>"
                                type="submit"><?= (bool)$user->admin ? "rétrograder" : "promouvoir" ?></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>