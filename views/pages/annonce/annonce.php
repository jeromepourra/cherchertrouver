<?php
$bOwner = FrontData::$data->get("annonce", "owner");
$aUser = FrontData::$data->get("annonce", "user");
$aAnnonce = FrontData::$data->get("annonce", "annonce");
?>

<div class="card m-auto" style="max-width: 750px;">

    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= $aAnnonce["annonce"]["title"] ?>
        </h1>
    </div>

    <div class="card-body p-0">

        <section>

            <div id="annonce-carousel" class="carousel slide" data-bs-ride="true">
                <div class="carousel-indicators">
                    <?php foreach ($aAnnonce["pictures"] as $nIndex => $aPicture) : ?>
                        <button type="button" data-bs-target="#annonce-carousel" data-bs-slide-to="<?= $nIndex ?>" class="<?= $nIndex === 0 ? "active" : "" ?>" aria-current="true" aria-label="Image <?= $nIndex ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($aAnnonce["pictures"] as $nIndex => $aPicture) : ?>
                        <div class="carousel-item <?= $nIndex === 0 ? "active" : "" ?>">
                            <img class="d-block w-100" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . "/" . $aPicture["annonce_id"] . "/" . $aPicture["_id"] . "." . $aPicture["extension"]; ?>" alt="*">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev carousel-arrow" type="button" data-bs-target="#annonce-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next carousel-arrow" type="button" data-bs-target="#annonce-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="container p-3">
                <div class="row g-3">
                    <?php foreach ($aAnnonce["pictures"] as $nIndex => $aPicture) : ?>
                        <div class="col-4 col-sm-3 col-md-2">
                            <img class="d-block w-100 rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . "/" . $aPicture["annonce_id"] . "/" . $aPicture["_id"] . "." . $aPicture["extension"]; ?>" alt="*" data-bs-target="#annonce-carousel" data-bs-slide-to="<?= $nIndex ?>" class="active">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </section>

    </div>

    <div class="card-body">

        <div class="card-text">
            <div>
                <p class="fs-5 fw-bold"><?= NumberFormat::format($aAnnonce["annonce"]["price"]) ?> €</p>
                <p class="d-inline-block text-bg-primary rounded py-1 px-3"><?= $aAnnonce["category"] ?></p>
                <p><?= $aAnnonce["annonce"]["description"] ?></p>
                <small class="col-8 text-muted"><?= DateFormat::format($aAnnonce["annonce"]["date"]) ?></small>
            </div>
            <hr>
            <div>
                <div class="row mb-3">
                    <h3 class="col-12 col-md-4 fs-5 mb-0">Contacter <span class="fw-bold"><?= $aUser["pseudo"] ?></span></h3>
                    <?php if (Session::userConnected()) : ?>
                        <small class="col-12 col-md-8 d-block text-muted text-md-end">Cet utilisateur favorise le contact par <span class="fw-bold"><?= FavoriteContact::getName($aUser["contact_favori"]) ?></span></small>
                    <?php endif; ?>
                </div>
                <?php if (Session::userConnected()) : ?>
                    <p class="mb-2">
                        <a class="link-primary text-decoration-none" href="mailto:" <?= $aUser["email"] ?>>
                            <i class="fa-solid fa-envelope"></i> <?= $aUser["email"] ?>
                        </a>
                    </p>
                    <p class="mb-2">
                        <a class="link-primary text-decoration-none" href="tel:+33<?= substr($aUser["phone"], 1) ?>">
                            <i class="fa-solid fa-phone"></i> <?= NumberFormat::formatPhone($aUser["phone"]) ?>
                        </a>
                    </p>
                    <button class="btn btn-sm btn-primary <?= $bOwner || $aUser["banned"] ? "disabled" : "" ?>">
                        <a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL("ConversationCreate", [$aAnnonce["annonce"]["_id"]]) ?>">
                            <i class="fa-solid fa-globe"></i> Envoyer un message
                        </a>
                    </button>
                <?php else : ?>
                    <p class="mb-0">Vous devez vous connecter pour afficher les moyens de contact de cet utilisateur</p>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <small class="d-block text-muted">Membre depuis <?= DateFormat::formatSinceMonth($aUser["inscription_date"]) ?></small>
        <small class="d-block text-muted">
            Voir toutes les annonce de <a class="link-primary" href="<?= RouterDictionnary::getURL("AnnonceUser") . "/" . $aUser["_id"] ?>"><?= $aUser["pseudo"] ?></a>
            <?php if ($aUser["banned"]) : ?>
                <span class="text-bg-danger rounded px-2">Compte banni</span>
            <?php endif; ?>
        </small>
    </div>

    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL() ?>">Retour à l'accueil</a></p>
    </div>

</div>