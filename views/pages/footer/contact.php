<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p class="mb-0">
                Vous souhaitez entrer en contact avec les membres du site ? <br>
                Écrivez nous en remplissant le formulaire ci-dessous
            </p>
        </div>
        <form action="<?= RouterDictionnary::getURL("Contact") ?>" method="post">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="firstname">Prénom</label>
                <?php if (Session::userConnected()) : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("firstname") ?>" type="text" id="firstname" name="firstname" readonly value="<?= Session::userGet()["firstname"] ?>">
                <?php else : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("firstname") ?>" type="text" id="firstname" name="firstname" value="<?= FrontForm::putFieldValue("firstname") ?>">
                <?php endif; ?>
                <?php FrontForm::printFieldErrors("firstname") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="lastname">Nom</label>
                <?php if (Session::userConnected()) : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("lastname") ?>" type="text" id="lastname" name="lastname" readonly value="<?= Session::userGet()["lastname"] ?>">
                <?php else : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("lastname") ?>" type="text" id="lastname" name="lastname" value="<?= FrontForm::putFieldValue("lastname") ?>">
                <?php endif; ?>
                <?php FrontForm::printFieldErrors("lastname") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <?php if (Session::userConnected()) : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("email") ?>" type="email" id="email" name="email" readonly value="<?= Session::userGet()["email"] ?>">
                <?php else : ?>
                    <input class="form-control <?= FrontForm::printFieldClass("email") ?>" type="email" id="email" name="email" value="<?= FrontForm::putFieldValue("email") ?>">
                <?php endif; ?>
                <?php FrontForm::printFieldErrors("email") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="message">Message</label>
                <textarea class="form-control <?= FrontForm::printFieldClass("message") ?>" name="message" id="message" cols="30" rows="4"><?= FrontForm::putFieldValue("message") ?></textarea>
                <?php FrontForm::printFieldErrors("message") ?>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Envoyer">
            </div>
        </form>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL() ?>">Retour à l'accueil</a></p>
    </div>
</div>