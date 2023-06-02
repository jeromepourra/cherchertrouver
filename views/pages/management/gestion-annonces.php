<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");
$aAnnonces = FrontData::$data->get("annonce", "annonces");
$nAnnoncesCount = isset($aAnnonces) ? count($aAnnonces) : 0;
$nTotalAnnonces = FrontData::$data->get("annonce", "total-annonces");
$nTotalPages = FrontData::$data->get("annonce", "total-pages");

function validationModal($sAnnonceId, $sHtmlId, $sAction, $sCssBtnClass) {
    echo "
        <button type='button' class='btn btn-sm btn-" . $sCssBtnClass . "' data-bs-toggle='modal' data-bs-target='#" . $sHtmlId . "'>" . ucfirst($sAction) . "</button>
        <div class='modal fade' id='" . $sHtmlId . "' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header text-bg-primary'>
                        <h6 class='modal-title fs-4'>" . ucfirst($sAction) . "</h6>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                        Êtes-vous certain de vouloir " . $sAction . " cette annonce ?
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Non</button>
                        <button type='button' class='btn btn-" . $sCssBtnClass . "'>
                            <a class='text-light text-decoration-none' href='" . RouterDictionnary::buildURL('ManageAnnonces', [$sAction, $sAnnonceId]) . "'>
                                Oui, " . $sAction . " cette annonce !
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    ";
}
?>

<div class="card m-auto" style="max-width: 1000px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">Rechercher</h1>
    </div>
    <div class="card-body">

        <form action="<?= RouterDictionnary::buildURL("ManageAnnonces", ["page", 1]) ?>" method="get" data-prevent-empty-get="true">
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
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text">État</span>
                                <select class="form-select <?= FrontForm::printFieldClass("state") ?>" name="state" id="state">
                                    <option value="" <?= FrontForm::putSelectFieldValue("state", "none") ?>>Tous</option>
                                    <?php foreach (AnnonceState::ANNONCE_STATES as $nState => $sState) : ?>
                                        <option value="<?= $nState ?>" <?= FrontForm::putSelectFieldValue("state", $nState) ?>><?= $sState ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php FrontForm::printFieldErrors("sort") ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <input class="btn btn-primary" type="submit" value="Rechercher">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php if (isset($aAnnonces)) : ?>

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
                    <div id="annonce-<?= $aAnnonce["annonce"]["_id"] ?>" class="row <?= ($nIndex < $nAnnoncesCount - 1 ? "mb-3" : "") ?>">
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
                                    <?php AnnonceState::printState($aAnnonce["annonce"]["state"]) ?>
                                    <small class="d-block text-muted">Par <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [$aAnnonce["user"]["_id"]]) ?>"><?= $aAnnonce["user"]["pseudo"] ?></a></small>
                                    <small class="d-block text-muted"><?= DateFormat::format($aAnnonce["annonce"]["date"]) ?></small>
                                </div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <a class="btn btn-primary" href="<?= RouterDictionnary::getURL("Annonce") . "/" . $aAnnonce["annonce"]["_id"] ?>">Détails</a>
                                    <div>
                                        <?php if ($aAnnonce["annonce"]["state"] == AnnonceState::ANNONCE_STATE_PENDING) : ?>
                                            <?php validationModal($aAnnonce["annonce"]["_id"], "modal-accept-annonce-" . $nIndex, "valider", "success") ?>
                                            <?php validationModal($aAnnonce["annonce"]["_id"], "modal-refuse-annonce-" . $nIndex, "refuser", "danger") ?>
                                        <?php elseif ($aAnnonce["annonce"]["state"] == AnnonceState::ANNONCE_STATE_ONLINE) : ?>
                                            <?php validationModal($aAnnonce["annonce"]["_id"], "modal-remove-annonce-" . $nIndex, "supprimer", "danger") ?>
                                        <?php elseif ($aAnnonce["annonce"]["state"] == AnnonceState::ANNONCE_STATE_REFUSED || $aAnnonce["annonce"]["state"] == AnnonceState::ANNONCE_STATE_REMOVED) : ?>
                                            <?php validationModal($aAnnonce["annonce"]["_id"], "modal-publish-annonce-" . $nIndex, "publier", "success") ?>
                                        <?php endif; ?>
                                    </div>
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

        <?php endif; ?>

    </div>

</div>