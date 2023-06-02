<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");
$aRate = FrontData::$data->get("rate");
$bOwner = FrontData::$data->get("annonce", "owner");
$aUser = FrontData::$data->get("annonce", "user");
$aAnnonces = FrontData::$data->get("annonce", "annonces");
$nAnnoncesCount = count($aAnnonces);

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

<div class="card m-auto" style="max-width: 1000px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">

        <?php if (!empty($aUser["bio"])) : ?>
            <p class="mb-2">Bio : <?= $aUser["bio"] ?></p>
        <?php endif; ?>

        <div class="d-flex flex-wrap align-items-end mb-2">
            <?php if ($aRate["can-rate"]) : ?>
                <p class="text-muted me-2 mb-0">Note</p>
                <div>
                    <?php for ($i = Constants::RATE_VAL_MIN; $i <= Constants::RATE_VAL_MAX; $i++) : ?>
                        <a class="on-rate <?= $aRate["value"] >= $i ? "be-rated-active" : "" ?>" href="<?= Router::buildURL("RateUser", ["user", $aUser["_id"], "rate", $i]) ?>"><i class="fa-solid fa-star"></i></a>
                    <?php endfor; ?>
                </div>
            <?php else : ?>
                <?php if ($bOwner) : ?>
                    <p class="text-muted me-2 mb-0">Votre note</p>
                <?php else : ?>
                    <p class="text-muted me-2 mb-0">Note</p>
                <?php endif; ?>
                <div>
                    <?php for ($i = Constants::RATE_VAL_MIN; $i <= Constants::RATE_VAL_MAX; $i++) : ?>
                        <i class="fa-solid fa-star be-rated <?= $aRate["value"] >= $i ? "be-rated-active" : "" ?>"></i>
                    <?php endfor; ?>
                </div>
                <?php if (!$bOwner) : ?>
                    <p class="text-muted ms-2 mb-0">(<?= $aRate["count"] ?> vote<?= ((int) $aRate["count"] > 1 ? "s" : "") ?>) vous avez attribué <?= $aRate["your-rate"] . " sur " . Constants::RATE_VAL_MAX ?> à cet utilisateur</p>
                <?php else : ?>
                    <p class="text-muted ms-2 mb-0">(<?= $aRate["count"] ?> vote<?= ((int) $aRate["count"] > 1 ? "s" : "") ?>)</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>


        <div class="text-muted d-flex justify-content-between align-items-end">
            <?php if ($bOwner) : ?>
                <p class="mb-0 me-3">Vous êtes membre depuis <?= DateFormat::formatSinceMonth($aUser["inscription_date"]) ?></p>
            <?php else : ?>
                <p class="mb-0 me-3">
                    <?= $aUser["pseudo"] ?> est membre depuis <?= DateFormat::formatSinceMonth($aUser["inscription_date"]) ?>
                    <?php if ($aUser["banned"]) : ?>
                        <span class="text-bg-danger rounded px-2">Compte banni</span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <div class="dropdown">
                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Trier
                </button>
                <ul class="dropdown-menu overflow-hidden p-0">
                    <li><a class="dropdown-item <?php dropdownActive(null) ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions) ?>">Aucun tri</a></li>
                    <li><a class="dropdown-item <?php dropdownActive("categorie") ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions, ["sort" => "categorie"]) ?>">Categorie</a></li>
                    <li><a class="dropdown-item <?php dropdownActive("recent") ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions, ["sort" => "recent"]) ?>">Plus récentes</a></li>
                    <li><a class="dropdown-item <?php dropdownActive("ancien") ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions, ["sort" => "ancien"]) ?>">Plus anciennes</a></li>
                    <li><a class="dropdown-item <?php dropdownActive("prixcroissant") ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions, ["sort" => "prixcroissant"]) ?>">Prix croissants</a></li>
                    <li><a class="dropdown-item <?php dropdownActive("prixdecroissant") ?>" href="<?= RouterDictionnary::buildURL($sControllerName, $aControllerActions, ["sort" => "prixdecroissant"]) ?>">Prix décroissants</a></li>
                </ul>
            </div>
        </div>

        <hr>

        <?php if ($nAnnoncesCount === 0) : ?>
            <?php if ($bOwner) : ?>
                <p>Oups, il semblerai que vous n'avez pas encore posté d'annonce...</p>
                <a class="btn btn-primary" href="<?= RouterDictionnary::getURL("AnnoncePost") ?>">Déposer une annonce</a>
            <?php else : ?>
                <p class="m-0">Oups, il semblerai que <span class="fw-bold"><?= $aUser["pseudo"] ?></span> n'a pas encore posté d'annonce...</p>
            <?php endif; ?>
        <?php else : ?>
            <?php foreach (FrontData::$data->get("annonce", "annonces") as $nIndex => $aAnnonce) : ?>
                <div class="row <?= ($nIndex < $nAnnoncesCount - 1 ? "mb-3" : "") ?>">
                    <div class="col-12 col-md-6">
                        <img class="img-cover rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . "/" . $aAnnonce["picture"]["annonce_id"] . "/" . $aAnnonce["picture"]["_id"] . "." . $aAnnonce["picture"]["extension"] ?>" alt="">
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <h2 class="text-truncate fs-5 mb-0"><?= $aAnnonce["annonce"]["title"] ?></h2>
                        <div>
                            <p class="fw-bold fs-6"><?= NumberFormat::format($aAnnonce["annonce"]["price"]) ?> €</p>
                            <p class="fw-bold mb-1"><?= $aAnnonce["category"] ?></p>
                            <p class="text-truncate"><?= $aAnnonce["annonce"]["description"] ?></p>
                        </div>
                        <div>
                            <div class="mb-3">
                                <?php if ($bOwner) : ?>
                                    <?php AnnonceState::printState($aAnnonce["annonce"]["state"]) ?>
                                <?php endif; ?>
                                <small class="d-block text-muted"><?= DateFormat::format($aAnnonce["annonce"]["date"]) ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <a class="btn btn-primary" href="<?= RouterDictionnary::getURL("Annonce") . "/" . $aAnnonce["annonce"]["_id"] ?>">Détails</a>
                                <?php if ($bOwner) : ?>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-primary">
                                            <a class="text-light text-decoration-none" href="<?= RouterDictionnary::getURL("AnnonceUpdate") . "/" . $aAnnonce["annonce"]["_id"] ?>">
                                                Modifier
                                            </a>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modal-remove-annonce-<?= $nIndex ?>">
                                            Retirer
                                        </button>
                                        <div class="modal fade" id="modal-remove-annonce-<?= $nIndex ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header text-bg-primary">
                                                        <h6 class="modal-title fs-4">Retirer</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Êtes-vous certain de vouloir retirer votre annonce ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                                        <button type="button" class="btn btn-danger">
                                                            <a class="text-light text-decoration-none" href="<?= RouterDictionnary::getURL("AnnonceDelete") . "/" . $aAnnonce["annonce"]["_id"] ?>">
                                                                Oui, retirer mon annonce !
                                                            </a>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($nIndex < $nAnnoncesCount - 1) : ?>
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL() ?>">Retour à l'accueil</a></p>
    </div>
</div>