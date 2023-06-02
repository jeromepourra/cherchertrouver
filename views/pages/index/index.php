<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");

function dropdownActive($sValue)
{
    if (isset($_GET["sort"])) {
        if ($_GET["sort"] == $sValue) {
            echo "active";
        }
    } else {
        if ($sValue == null) {
            echo "active";
        }
    }
}

?>

<div class="m-auto mb-5 line-height-0" style="max-width: 1000px;">

    <div class="text-center text-bg-dark rounded p-2 mb-5">
        <h1>Bienvenue sur Chercher & Trouver</h1>
        <p class="mb-0">La référence des petites annonces de matériel informatique sur Sète et ses alentours</p>
    </div>

    <div class="text-center mb-5">
        <p class="mb-0">Vous vivez sur Sète ou à proximité, et vous avez besoin de <strong>vendre</strong> ou d'<strong>acheter</strong> du matériel informatique près de chez vous ?</p>
        <p class="mb-0"><strong>Alors vous êtes un bon endroit !</strong></p>
    </div>

    <div>
        <p class="mb-0"><strong>Vous voulez acheter du matériel ?</strong></p>
        <?php if (Session::userConnected()) : ?>
            <p class="">Consultez nos annonces à l'aide du formulaire de recherche ci-dessous.</p>
        <?php else : ?>
            <p class=""><a href="<?= RouterDictionnary::buildURL("Signin") ?>">Connectez-vous</a> puis consultez nos annonces à l'aide du formulaire de recherche ci-dessous.</p>
        <?php endif; ?>
    </div>

    <div>
        <p class="mb-0"><strong>Vous voulez vendre votre matériel ?</strong></p>
        <?php if (Session::userConnected()) : ?>
            <p class="">Déposer une annonce en cliquant <a href="<?= RouterDictionnary::buildURL("AnnoncePost") ?>">ici</a>.</p>
        <?php else : ?>
            <p class=""><a href="<?= RouterDictionnary::buildURL("Signin") ?>">Connectez-vous</a> puis déposer une annonce.</p>
        <?php endif; ?>
    </div>

</div>

<div class="card m-auto" style="max-width: 1000px;">
    <div class="card-header text-center text-bg-primary">
        <h2 class="fs-5 fw-bold mb-0">Recherche d'annonces</h2>
    </div>
    <div class="card-body">
        <form action="<?= RouterDictionnary::buildURL("Search", ["page", 1]) ?>" method="get" data-prevent-empty-get="true">
            <div class="mb-3">
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label class="form-label" for="category">Par catégorie</label>
                        <select class="form-select <?= FrontForm::printFieldClass("category") ?>" name="category" id="category">
                            <option value="0">Toutes catégories</option>
                            <?php foreach (FrontData::$data->get("annonce", "categories") as $aCategory) : ?>
                                <option value="<?= $aCategory["_id"] ?>" <?= FrontForm::putSelectFieldValue("category", $aCategory["_id"]) ?>><?= $aCategory["name"] ?></option>
                            <?php endforeach ?>
                        </select>
                        <?php FrontForm::printFieldErrors("category") ?>
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label" for="key-word">Par mot clef</label>
                        <input class="form-control <?= FrontForm::printFieldClass("key-word") ?>" type="text" id="key-word" name="key-word" value="<?= FrontForm::putFieldValue("key-word") ?>" placeholder="Recherche par mot clef...">
                        <?php FrontForm::printFieldErrors("key-word") ?>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label" for="price-min">Prix min</label>
                        <div class="input-group">
                            <input class="form-control <?= FrontForm::printFieldClass("price-min") ?>" type="number" id="price-min" name="price-min" min="0" step="0.01" value="<?= FrontForm::putFieldValue("price-min") ?>">
                            <span class="input-group-text">€</span>
                        </div>
                        <?php FrontForm::printFieldErrors("price-min") ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="price-min">Prix max</label>
                        <div class="input-group">
                            <input class="form-control <?= FrontForm::printFieldClass("price-max") ?>" type="number" id="price-max" name="price-max" min="0" step="0.01" value="<?= FrontForm::putFieldValue("price-max") ?>">
                            <span class="input-group-text">€</span>
                        </div>
                        <?php FrontForm::printFieldErrors("price-max") ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text">Trier</span>
                        <select class="form-select <?= FrontForm::printFieldClass("sort") ?>" name="sort" id="sort">
                            <option value="" <?= FrontForm::putSelectFieldValue("sort", "none") ?>>Aucun tri</option>
                            <option value="categorie" <?= FrontForm::putSelectFieldValue("sort", "categorie") ?>>Categorie</option>
                            <option value="recent" <?= FrontForm::putSelectFieldValue("sort", "recent") ?>>Plus récentes</option>
                            <option value="ancien" <?= FrontForm::putSelectFieldValue("sort", "ancien") ?>>Plus anciennes</option>
                            <option value="prixcroissant" <?= FrontForm::putSelectFieldValue("sort", "prixcroissant") ?>>Prix croissants</option>
                            <option value="prixdecroissant" <?= FrontForm::putSelectFieldValue("sort", "prixdecroissant") ?>>Prix décroissants</option>
                        </select>
                    </div>
                    <?php FrontForm::printFieldErrors("sort") ?>
                </div>
                <div class="col-12 col-md-6 text-start text-md-end">
                    <input class="btn btn-primary" type="submit" value="Rechercher">
                </div>
            </div>
        </form>
    </div>
</div>