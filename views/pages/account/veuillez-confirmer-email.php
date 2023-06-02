<div class="card m-auto" style="max-width: 750px;">

    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            Votre adresse email n'a pas encore été validé
        </h1>
    </div>

    <div class="card-body">

        <p>Pour acceder à cette page vous devez <b>confirmer votre adresse email</b>.</p>
        <p>Un e-mail de confirmation vous a été envoyé à l'adresse indiqué lors de votre inscription.</p>

        <p><b>Si ce n'est pas votre adresse email</b> : vous pouvez la modifier sur la page <a href="<?= RouterDictionnary::buildURL("MyAccount") ?>">Mon Compte</a> dans l'onglet <b>Modifier mon email</b></p>
        <p><b>Si vous n'avez rien reçu</b> : vous pouvez renvoyer un code sur la page <a href="<?= RouterDictionnary::buildURL("MyAccount") ?>">Mon Compte</a> dans l'onglet <b>Modifier mon email</b></p>

        <ul class="list-group">
            <li class="list-group-item text-bg-dark">Après validation de votre email vous aurez la possibilité de :</li>
            <li class="list-group-item">- Déposer vos annonces</li>
            <li class="list-group-item">- Contacter les utilisateurs</li>
            <li class="list-group-item">- Noter les utilisateurs</li>
        </ul>

    </div>

    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL("MyAccount") ?>">Mon Compte</a></p>
    </div>

</div>