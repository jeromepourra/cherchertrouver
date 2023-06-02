<?php
$aUser = FrontData::$data->get("account", "user");
?>

<div class="card m-auto" style="max-width: 750px;">

    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>

    <div class="card-body">

        <p>Votre adresse email viens d'être vérifiée. <br> Vous avez désormais accès à l'intégralité de notre site web.</p>

        <ul class="list-group mb-3">
            <li class="list-group-item text-bg-dark">Vous avez la possibilité de :</li>
            <li class="list-group-item">- Déposer vos annonces</li>
            <li class="list-group-item">- Contacter les utilisateurs</li>
            <li class="list-group-item">- Noter les utilisateurs</li>
        </ul>

        <hr>

        <ul class="list-group">
            <li class="list-group-item text-bg-dark">Rappel de vos informations personnelles : </li>
            <li class="list-group-item">Pseudo : <?= $aUser["pseudo"] ?></li>
            <li class="list-group-item">Nom : <?= $aUser["firstname"] ?></li>
            <li class="list-group-item">Prénom : <?= $aUser["lastname"] ?></li>
            <li class="list-group-item">Email : <?= $aUser["email"] ?></li>
        </ul>

    </div>

    <div class="card-footer text-center text-bg-primary">
        <?php if (Session::userConnected()) : ?>
            <p class="m-0">Commencer par déposer une annonce ? <a class="link-dark" href="<?= RouterDictionnary::buildURL("AnnoncePost") ?>">Déposer une annonce</a></p>
        <?php else : ?>
            <p class="m-0">Commencer par se connecter ? <a class="link-dark" href="<?= RouterDictionnary::buildURL("Signin") ?>">Connectez-vous</a></p>
        <?php endif; ?>
    </div>

</div>