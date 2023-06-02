<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p><strong>En vous inscrivant vous accedez à l'intégralité de notre site :</strong></p>
            <ul class="mb-0">
                <li>Poster vos annonces de <strong>matériel informatique</strong></li>
                <li>Contacter, Discuter, Noter les utilisateurs depuis le site</li>
                <li>Et bien plus encore...</li>
            </ul>
        </div>
        <form action="<?= RouterDictionnary::getURL("Signup") ?>" method="post">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="pseudo">Pseudo</label>
                <input class="form-control <?= FrontForm::printFieldClass("pseudo") ?>" type="text" id="pseudo" name="pseudo" value="<?= FrontForm::putFieldValue("pseudo") ?>">
                <?php FrontForm::printFieldErrors("pseudo") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="firstname">Prénom</label>
                <input class="form-control <?= FrontForm::printFieldClass("firstname") ?>" type="text" id="firstname" name="firstname" value="<?= FrontForm::putFieldValue("firstname") ?>">
                <?php FrontForm::printFieldErrors("firstname") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="lastname">Nom</label>
                <input class="form-control <?= FrontForm::printFieldClass("lastname") ?>" type="text" id="lastname" name="lastname" value="<?= FrontForm::putFieldValue("lastname") ?>">
                <?php FrontForm::printFieldErrors("lastname") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input class="form-control <?= FrontForm::printFieldClass("email") ?>" type="email" id="email" name="email" value="<?= FrontForm::putFieldValue("email") ?>">
                <?php FrontForm::printFieldErrors("email") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Numéro de téléphone</label>
                <input class="form-control <?= FrontForm::printFieldClass("phone") ?>" type="tel" id="phone" name="phone" value="<?= FrontForm::putFieldValue("phone") ?>">
                <?php FrontForm::printFieldErrors("phone") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="birthday">Date de naissance</label>
                <input class="form-control <?= FrontForm::printFieldClass("birthday") ?>" type="date" id="birthday" name="birthday" value="<?= FrontForm::putFieldValue("birthday") ?>">
                <?php FrontForm::printFieldErrors("birthday") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Mot de passe</label>
                <input class="form-control <?= FrontForm::printFieldClass("password") ?>" type="password" id="password" name="password" value="<?= FrontForm::putFieldValue("password") ?>">
                <?php FrontForm::printFieldErrors("password") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password-conf">Confirmation du mot de passe</label>
                <input class="form-control <?= FrontForm::printFieldClass("password-conf") ?>" type="password" id="password-conf" name="password-conf" value="<?= FrontForm::putFieldValue("password-conf") ?>">
                <?php FrontForm::printFieldErrors("password-conf") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="bio">Bio (optionnel)</label>
                <textarea class="form-control <?= FrontForm::printFieldClass("bio") ?>" name="bio" id="bio" cols="30" rows="4"><?= FrontForm::putFieldValue("bio") ?></textarea>
                <?php FrontForm::printFieldErrors("bio") ?>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="check-major" value="off">
                    <input class="form-check-input <?= FrontForm::printFieldClass("check-major") ?>" type="checkbox" id="check-major" name="check-major">
                    <label class="form-check-label" for="check-major">Je certifie être majeur.</label>
                    <?php FrontForm::printFieldErrors("check-major", "text-start") ?>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="check-cgu" value="off">
                    <input class="form-check-input  <?= FrontForm::printFieldClass("check-cgu") ?>" type="checkbox" id="check-cgu" name="check-cgu">
                    <label class="form-check-label" for="check-cgu">Je certifie que les informations remplies dans le formulaire ci-dessus sont exactes.</label>
                    <?php FrontForm::printFieldErrors("check-cgu", "text-start") ?>
                </div>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="S'inscrire">
            </div>
        </form>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0">Vous avez déjà un compte ? <a class="link-dark" href="<?= RouterDictionnary::buildURL("Signin"); ?>">Connectez-vous</a></p>
    </div>
</div>