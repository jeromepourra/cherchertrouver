<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");
$aAnnonce = FrontData::$data->get("annonce", "annonce", "annonce");
$aPictures = FrontData::$data->get("annonce", "annonce", "pictures");
?>

<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p><b>Modifiez votre annonce</b>, changez les informations la concernant, retirez ou ajoutez des images, puis validez.</p>
            <p>Une fois modifiée, l'annonce devra être validée par l'un de nos modérateur avant de (re)devenir visible par les autres utilisateurs.</p>
            <p class="mb-0">Vous pourrez consulter les états de vos annonces <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [Session::userGetId()]) ?>">ici</a></p>
        </div>
        <form action="<?= RouterDictionnary::buildURL($sControllerName, [$aAnnonce["_id"]]) ?>" method="post" enctype="multipart/form-data">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category">Catégorie</label>
                <select class="form-select <?= FrontForm::printFieldClass("category") ?>" name="category" id="category">
                    <option value="">Selectionner</option>
                    <?php foreach (FrontData::$data->get("annonce", "categories") as $aCategory) : ?>
                        <option value="<?= $aCategory["_id"] ?>" <?= FrontForm::putSelectFieldValueFromData($aAnnonce["category_id"], $aCategory["_id"]) ?>><?= $aCategory["name"] ?></option>
                    <?php endforeach ?>
                </select>
                <?php FrontForm::printFieldErrors("category") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="title">Titre</label>
                <input class="form-control <?= FrontForm::printFieldClass("title") ?>" type="text" id="title" name="title" value="<?= $aAnnonce["title"] ?>">
                <?php FrontForm::printFieldErrors("title") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="price">Prix</label>
                <input class="form-control <?= FrontForm::printFieldClass("price") ?>" type="number" id="price" name="price" min="0" step="0.01" value="<?= $aAnnonce["price"] ?>">
                <?php FrontForm::printFieldErrors("price") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control <?= FrontForm::printFieldClass("description") ?>" name="description" id="description" cols="30" rows="4"><?= $aAnnonce["description"] ?></textarea>
                <?php FrontForm::printFieldErrors("description") ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Images</label>
                <div class="container p-0">
                    <div class="row g-3">
                        <?php foreach ($aPictures as $nIndex => $aPicture) : ?>
                            <div class="col-4 col-sm-3 col-md-2">
                                <img class="d-block w-100 rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . "/" . $aPicture["annonce_id"] . "/" . $aPicture["_id"] . "." . $aPicture["extension"]; ?>" alt="*">
                                <a class="btn btn-sm btn-danger w-100 mt-1 text-light text-decoration-none" href="<?= RouterDictionnary::buildURL($sControllerName, [$aAnnonce["_id"], "retirer-image", $aPicture["_id"]]) ?>">Retirer</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php FrontForm::printFieldErrors("pictures") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="new-pictures">Selectionner une ou plusieurs images</label>
                <input class="form-control" type="file" name="new-pictures[]" id="new-pictures" accept="image/gif, image/jpeg, image/png" multiple>
                <?php FrontForm::printFieldErrors("new-pictures") ?>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Modifier l'annonce">
                <a class="btn btn-danger" href="<?= RouterDictionnary::buildURL("AnnonceUser", [Session::userGetId()]) ?>">Annuler</a>
            </div>
        </form>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL("AnnonceUser", [Session::userGetId()]) ?>">Consulter mes annonces</a></p>
    </div>
</div>