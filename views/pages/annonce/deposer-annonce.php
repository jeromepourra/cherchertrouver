<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p><b>Déposez une annonce</b> correspondant à l'une des catégorie ci-dessous, ajoutez y les informations la concernant, une ou plusieurs images, puis validez.</p>
            <p>Une fois postée, l'annonce devra être <b>validée</b> par l'un de nos modérateur avant de devenir <b>visible</b> par les autres utilisateurs.</p>
            <p class="mb-0">Vous pourrez consulter les états de vos annonces <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [Session::userGetId()]) ?>">ici</a></p>
        </div>
        <form action="<?= RouterDictionnary::getURL("AnnoncePost") ?>" method="post" enctype="multipart/form-data">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category">Catégorie</label>
                <select class="form-select <?= FrontForm::printFieldClass("category") ?>" name="category" id="category">
                    <option value="">Selectionner</option>
                    <?php foreach (FrontData::$data->get("annonce", "categories") as $aCategory) : ?>
                        <option value="<?= $aCategory["_id"] ?>" <?= FrontForm::putSelectFieldValue("category", $aCategory["_id"]) ?>><?= $aCategory["name"] ?></option>
                    <?php endforeach ?>
                </select>
                <?php FrontForm::printFieldErrors("category") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="title">Titre</label>
                <input class="form-control <?= FrontForm::printFieldClass("title") ?>" type="text" id="title" name="title" value="<?= FrontForm::putFieldValue("title") ?>">
                <?php FrontForm::printFieldErrors("title") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="price">Prix</label>
                <input class="form-control <?= FrontForm::printFieldClass("price") ?>" type="number" id="price" name="price" min="0" step="0.01" value="<?= FrontForm::putFieldValue("price") ?>">
                <?php FrontForm::printFieldErrors("price") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control <?= FrontForm::printFieldClass("description") ?>" name="description" id="description" cols="30" rows="4"><?= FrontForm::putFieldValue("description") ?></textarea>
                <?php FrontForm::printFieldErrors("description") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="pictures">Selectionner une ou plusieurs images</label>
                <input class="form-control" type="file" name="pictures[]" id="pictures" accept="image/gif, image/jpeg, image/png" multiple>
                <?php FrontForm::printFieldErrors("pictures") ?>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Déposer l'annonce">
                <a class="btn btn-danger" href="<?= Router::getReferer() ?>">Annuler</a>
            </div>
        </form>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL("AnnonceUser", [Session::userGetId()]) ?>">Consulter mes annonces</a></p>
    </div>
</div>