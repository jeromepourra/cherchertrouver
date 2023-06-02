<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p><strong>En vous connectant vous accedez à l'intégralité de notre site :</strong></p>
            <ul class="mb-0">
                <li>Poster vos annonces de <strong>matériel informatique</strong></li>
                <li>Contacter, Discuter, Noter les utilisateurs depuis le site</li>
                <li>Et bien plus encore...</li>
            </ul>
        </div>
        <form action="<?= RouterDictionnary::getURL("Signin") ?>" method="post">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="name">Pseudo ou adresse email</label>
                <input class="form-control <?= FrontForm::printFieldClass("name") ?>" type="text" id="name" name="name" value="<?= FrontForm::putFieldValue("name") ?>">
                <?php FrontForm::printFieldErrors("name") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Mot de passe</label>
                <input class="form-control <?= FrontForm::printFieldClass("password") ?>" type="password" id="password" name="password" value="<?= FrontForm::putFieldValue("password") ?>">
                <?php FrontForm::printFieldErrors("password") ?>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Se connecter">
            </div>
        </form>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0">Vous n'avez pas de compte ? <a class="link-dark" href="<?= RouterDictionnary::buildURL("Signup") ?>">Inscrivez-vous</a></p>
    </div>
</div>