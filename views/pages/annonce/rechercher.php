<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");
$aAnnonces = FrontData::$data->get("annonce", "annonces");
$nAnnoncesCount = isset($aAnnonces) ? count($aAnnonces) : 0;
$nTotalAnnonces = FrontData::$data->get("annonce", "total-annonces");
$nTotalPages = FrontData::$data->get("annonce", "total-pages");
?>

<div class="card m-auto" style="max-width: 1000px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">Recherche d'annonces</h1>
    </div>
    <div class="card-body">

        <form action="<?= RouterDictionnary::buildURL("Search", ["page", 1]) ?>" method="get" data-prevent-empty-get="true">
            <div>
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

        <hr>

        <?php if ($nAnnoncesCount === 0) : ?>
            <div class="text-center">
                <div class="text-center mb-3">
                    <img class="w-100" style="max-width: 500px;" src="<?= WEB_ROOT . "/views/public/img/no_results.jpg" ?>" alt="Aucun résultat">
                </div>
                <p class="mb-0"><span class="text-primary fs-1 fw-bold mb-0">Whoops...</span> <br> Aucun resultat ne semble correspondre à cette recherche</p>
            </div>
        <?php else : ?>
            <p class="text-center fw-bold text-bg-warning rounded p-1">
                <?= $nTotalAnnonces ?> résultat<?= $nTotalAnnonces > 1 ? "s" : "" ?> - <?= $nTotalPages ?> page<?= $nTotalPages > 1 ? "s" : "" ?>
            </p>
            <?php foreach (FrontData::$data->get("annonce", "annonces") as $nIndex => $aAnnonce) : ?>
                <div class="row <?= ($nIndex < $nAnnoncesCount - 1 ? "mb-3" : "") ?>">
                    <div class="col-12 col-md-6">
                        <img class="img-cover rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . "/" . $aAnnonce["picture"]["annonce_id"] . "/" . $aAnnonce["picture"]["_id"] . "." . $aAnnonce["picture"]["extension"] ?>" alt="">
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <h2 class="text-truncate fw-bold fs-5 mb-0"><?= $aAnnonce["annonce"]["title"] ?></h2>
                        <div>
                            <p class="fw-bold fs-6"><?= NumberFormat::format($aAnnonce["annonce"]["price"]) ?> €</p>
                            <p class="fw-bold mb-1"><?= $aAnnonce["category"] ?></p>
                            <p class="text-truncate"><?= $aAnnonce["annonce"]["description"] ?></p>
                        </div>
                        <div>
                            <div class="mb-3">
                                <small class="d-block text-muted">Par <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [$aAnnonce["user"]["_id"]]) ?>"><?= $aAnnonce["user"]["pseudo"] ?></a></small>
                                <small class="d-block text-muted"><?= DateFormat::format($aAnnonce["annonce"]["date"]) ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <a class="btn btn-primary" href="<?= RouterDictionnary::getURL("Annonce") . "/" . $aAnnonce["annonce"]["_id"] ?>">Détails</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($nIndex < $nAnnoncesCount - 1) : ?>
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <nav>
            <ul class='pagination justify-content-center mt-3 mb-0'>
                <?php FrontPagination::printSearchPagination(FrontData::$data->get("annonce")) ?>
            </ul>
        </nav>

    </div>

</div>