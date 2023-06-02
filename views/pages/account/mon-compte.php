<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p class="mb-0">Ci-dessous, vous pourrez modifier les informations ainsi que les paramètres de votre compte.</p>
        </div>
        <div class="card m-auto">
            <div class="card-header text-center text-bg-dark">
                <h1 class="mb-0 fw-bold fs-5">Modifier ma bio</h1>
            </div>
            <div class="card-body mb-3">
                <form action="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=bio" ?>" method="post">
                    <div>
                        <?php FrontForm::printFormError("form-bio") ?>
                        <?php FrontForm::printFormSuccess("form-bio") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-bio-bio">Nouvelle bio</label>
                        <textarea class="form-control <?= FrontForm::printFieldClass("change-bio-bio") ?>" name="change-bio-bio" id="change-bio-bio" cols="30" rows="4"><?= FrontData::$data->get("user", "bio") ?></textarea>
                        <?php FrontForm::printFieldErrors("change-bio-bio") ?>
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Changer sa bio">
                    </div>
                </form>
            </div>
            <div class="card-header text-center text-bg-dark">
                <h1 class="mb-0 fw-bold fs-5">Modifier mon contact favori</h1>
            </div>
            <div class="card-body mb-3">
                <form action="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=contact" ?>" method="post">
                    <div>
                        <?php FrontForm::printFormError("form-contact") ?>
                        <?php FrontForm::printFormSuccess("form-contact") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-contact-favori">Les autres utilisateurs doivent me contacter de préférence via</label>
                        <select class="form-select <?= FrontForm::printFieldClass("change-contact-favori") ?>" name="change-contact-favori" id="change-contact-favori">
                            <?php foreach (Constants::CONTACTS_FAVORI as $nValue => $sMethod) : ?>
                                <option value="<?= $nValue ?>" <?= FrontForm::putSelectFieldValueFromData(FrontData::$data->get("user", "contact_favori"), $nValue) ?>><?= $sMethod ?></option>
                            <?php endforeach ?>
                        </select>
                        <?php FrontForm::printFieldErrors("change-contact-favori") ?>
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Changer contact favori">
                    </div>
                </form>
            </div>
            <div class="card-header text-center text-bg-dark">
                <h1 class="mb-0 fw-bold fs-5">Modifier mon email</h1>
            </div>
            <div class="card-body mb-3">
                <form action="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=email" ?>" method="post">
                    <div>
                        <?php FrontForm::printFormError("form-email") ?>
                        <?php FrontForm::printFormSuccess("form-email") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-email-email">Votre adresse email</label>
                        <input class="form-control border-primary" readonly value="<?= FrontData::$data->get("user", "email") ?>">
                        <?php if (Session::userGetVerified()) : ?>
                            <small class="text-success">Votre adresse email est vérifiée</small>
                        <?php else : ?>
                            <small class="d-block text-danger">Votre adresse n'a pas encore été vérifiée, veuillez consulter vos email.</small>
                            <small class="d-block text-danger">Vous n'avez rien reçu ? <a class="" href="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=email-send-back" ?>">Renvoyer un email</a></small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-email-email">Nouvelle adresse email</label>
                        <input class="form-control <?= FrontForm::printFieldClass("change-email-email") ?>" type="email" id="change-email-email" name="change-email-email" value="<?= FrontForm::putFieldValue("change-email-email") ?>">
                        <?php FrontForm::printFieldErrors("change-email-email") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-email-password">Mot de passe</label>
                        <input class="form-control <?= FrontForm::printFieldClass("change-email-password") ?>" type="password" id="change-email-password" name="change-email-password" value="<?= FrontForm::putFieldValue("change-email-password") ?>">
                        <?php FrontForm::printFieldErrors("change-email-password") ?>
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Changer son adresse email">
                    </div>
                </form>
            </div>
            <div class="card-header text-center text-bg-dark">
                <h1 class="mb-0 fw-bold fs-5">Modifier mon mot de passe</h1>
            </div>
            <div class="card-body mb-3">
                <form action="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=password" ?>" method="post">
                    <div>
                        <?php FrontForm::printFormError("form-password") ?>
                        <?php FrontForm::printFormSuccess("form-password") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-password-old">Mot de passe actuel</label>
                        <input class="form-control <?= FrontForm::printFieldClass("change-password-old") ?>" type="password" id="change-password-old" name="change-password-old" value="<?= FrontForm::putFieldValue("change-password-old") ?>">
                        <?php FrontForm::printFieldErrors("change-password-old") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-password-new">Nouveau mot de passe</label>
                        <input class="form-control <?= FrontForm::printFieldClass("change-password-new") ?>" type="password" id="change-password-new" name="change-password-new" value="<?= FrontForm::putFieldValue("change-password-new") ?>">
                        <?php FrontForm::printFieldErrors("change-password-new") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="change-password-conf">Confirmer nouveau mot de passe</label>
                        <input class="form-control <?= FrontForm::printFieldClass("change-password-conf") ?>" type="password" id="change-password-conf" name="change-password-conf" value="<?= FrontForm::putFieldValue("change-password-conf") ?>">
                        <?php FrontForm::printFieldErrors("change-password-conf") ?>
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Changer son mot de passe">
                    </div>
                </form>
            </div>
            <div class="card-header text-center text-bg-danger">
                <h1 class="mb-0 fw-bold fs-5">Supprimer mon compte</h1>
            </div>
            <div class="card-body mb-3">
                <form action="<?= RouterDictionnary::getURL("MyAccount") . "?" . "action=remove" ?>" method="post">
                    <div>
                        <?php FrontForm::printFormError("form-account") ?>
                        <?php FrontForm::printFormSuccess("form-account") ?>
                    </div>
                    <p class="mb-3"><span class="text-danger fw-bold">Attention !</span> cette action est irreversible</p>
                    <div class="mb-3">
                        <label class="form-label" for="remove-account-password">Mot de passe</label>
                        <input class="form-control <?= FrontForm::printFieldClass("remove-account-password") ?>" type="password" id="remove-account-password" name="remove-account-password" value="<?= FrontForm::putFieldValue("remove-account-password") ?>">
                        <?php FrontForm::printFieldErrors("remove-account-password") ?>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="remove-account-check" value="off">
                            <input class="form-check-input  <?= FrontForm::printFieldClass("remove-account-check") ?>" type="checkbox" id="remove-account-check" name="remove-account-check">
                            <label class="form-check-label" for="remove-account-check">Je confirme vouloir supprimer mon compte, ainsi que toutes les annonces qui y sont associés</label>
                            <?php FrontForm::printFieldErrors("remove-account-check", "text-start") ?>
                        </div>
                    </div>
                    <div>
                        <input class="btn btn-danger" type="submit" value="Supprimer son compte">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL() ?>">Retour à l'accueil</a></p>
    </div>
</div>