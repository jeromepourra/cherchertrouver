<?php
$sControllerName = FrontData::$data->get("controller", "name");
$aControllerActions = FrontData::$data->get("controller", "actions");
$aUsers = FrontData::$data->get("user", "users");
$nUsersCount = isset($aUsers) ? count($aUsers) : 0;
$nTotalUsers = FrontData::$data->get("user", "total-users");
$nTotalPages = FrontData::$data->get("user", "total-pages");
?>

<div class="card m-auto" style="max-width: 1000px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">Rechercher</h1>
    </div>
    <div class="card-body">

        <form action="<?= RouterDictionnary::buildURL("ManageUsers", ["page", 1]) ?>" method="get" data-prevent-empty-get="true">
            <div class="mb-3">
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label class="form-label" for="role">Par rôle</label>
                        <select class="form-select <?= FrontForm::printFieldClass("role") ?>" name="role" id="role">
                            <option value="tous">Tous les rôles</option>
                            <?php foreach (Constants::USER_ROLES as $nIndex => $aRole) : ?>
                                <option value="<?= $nIndex ?>" <?= FrontForm::putSelectFieldValue("role", $nIndex) ?>><?= $aRole["name"] ?></option>
                            <?php endforeach ?>
                        </select>
                        <?php FrontForm::printFieldErrors("role") ?>
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label" for="key-word">Par pseudo</label>
                        <input class="form-control <?= FrontForm::printFieldClass("key-word") ?>" type="text" id="key-word" name="key-word" value="<?= FrontForm::putFieldValue("key-word") ?>" placeholder="Recherche par le pseudo...">
                        <?php FrontForm::printFieldErrors("key-word") ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="input-group">
                        <span class="input-group-text">Trier</span>
                        <select class="form-select <?= FrontForm::printFieldClass("sort") ?>" name="sort" id="sort">
                            <option value="" <?= FrontForm::putSelectFieldValue("sort", "none") ?>>Aucun tri</option>
                            <option value="recent" <?= FrontForm::putSelectFieldValue("sort", "recent") ?>>Inscription récentes</option>
                            <option value="ancien" <?= FrontForm::putSelectFieldValue("sort", "ancien") ?>>Inscription anciennes</option>
                            <option value="pseudocroissant" <?= FrontForm::putSelectFieldValue("sort", "pseudocroissant") ?>>Pseudo croissants</option>
                            <option value="pseudodecroissant" <?= FrontForm::putSelectFieldValue("sort", "pseudodecroissant") ?>>Pseudo décroissants</option>
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
                                    <option value="0" <?= FrontForm::putSelectFieldValue("state", "0") ?>>Actif</option>
                                    <option value="1" <?= FrontForm::putSelectFieldValue("state", "1") ?>>Banni</option>
                                </select>
                            </div>
                            <?php FrontForm::printFieldErrors("state") ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <input class="btn btn-primary" type="submit" value="Rechercher">
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php if (isset($aUsers)) : ?>

            <hr>

            <?php if ($nUsersCount === 0) : ?>
                <div class="text-center">
                    <div class="text-center mb-3">
                        <img class="w-100" style="max-width: 500px;" src="<?= WEB_ROOT . "/views/public/img/no_results.jpg" ?>" alt="Aucun résultat">
                    </div>
                    <p class="mb-0"><span class="text-primary fs-1 fw-bold mb-0">Whoops...</span> <br> Aucun resultat ne semble correspondre à cette recherche</p>
                </div>
            <?php else : ?>

                <p class="text-center fw-bold text-bg-warning rounded p-1">
                    <?= $nTotalUsers ?> résultat<?= $nTotalUsers > 1 ? "s" : "" ?> - <?= $nTotalPages ?> page<?= $nTotalPages > 1 ? "s" : "" ?>
                </p>
                <?php foreach ($aUsers as $nIndex => $aUser) : ?>
                    <div id="user-<?= $aUser["_id"] ?>" class="<?= ($nIndex < $nUsersCount - 1 ? "mb-3" : "") ?>">
                        <ul class="list-group">
                            <li class="list-group-item active text-center">
                                <?= $aUser["pseudo"] ?>
                            </li>
                            <li class="list-group-item">
                                <div>
                                    <?php FrontForm::printFormError("form-banish-" . $nIndex) ?>
                                    <?php FrontForm::printFormSuccess("form-banish-" . $nIndex) ?>
                                </div>
                                <div>
                                    <?php FrontForm::printFormError("form-role-" . $nIndex) ?>
                                    <?php FrontForm::printFormSuccess("form-role-" . $nIndex) ?>
                                </div>
                                <div class="row">
                                    <p class="col-12 col-md-6 m-0">Prénom : <?= $aUser["firstname"] ?></p>
                                    <p class="col-12 col-md-6 m-0">Nom : <?= $aUser["lastname"] ?></p>
                                </div>
                                <div class="row">
                                    <p class="col-12 col-md-6 m-0">
                                        Email : <?= $aUser["email"] ?>
                                        <?php if ($aUser["email_confirmation"]) : ?>
                                            <span class="text-bg-success text-center rounded px-1" style="width: 25px; display: inline-block;"><i class="fa-solid fa-check"></i></span>
                                        <?php else : ?>
                                            <span class="text-bg-danger text-center rounded px-1" style="width: 25px; display: inline-block;"><i class="fa-solid fa-xmark"></i></span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="col-12 col-md-6 m-0">Téléphone : <?= NumberFormat::formatPhone($aUser["phone"]) ?></p>
                                </div>
                                <div class="row mb-3">
                                    <p class="col-12 col-md-6 m-0">Rôle : <?= Constants::USER_ROLES[$aUser["role"]]["name"]  ?></p>
                                    <p class="col-12 col-md-6 m-0">
                                        <?php if ($aUser["banned"]) : ?>
                                            <span class="text-bg-danger rounded px-2">Compte banni</span>
                                        <?php else : ?>
                                            <span class="text-bg-success rounded px-2">Compte actif</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="row">
                                    <p class="text-muted m-0">Dernière connexion <?= (!empty($aUser["connection_date"]) ? DateFormat::format($aUser["connection_date"]) : "aucune") ?></p>
                                    <p class="text-muted m-0">Membre depuis <?= DateFormat::formatSinceMonth($aUser["inscription_date"]) ?></p>
                                    <p class="text-muted m-0">Voir les annonces de <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [$aUser["_id"]]) ?>"><?= $aUser["pseudo"] ?></a></p>
                                </div>
                            </li>
                            <?php if (Session::userGetId() != $aUser["_id"] && Session::userGetRole() > $aUser["role"] && !$aUser["banned"]) : ?>
                                <li class="list-group-item p-0 border-top-0">
                                    <div class="accordion accordion-flush" id="accordion-<?= $aUser["_id"] ?>">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="accordion-trigger-banish-<?= $aUser["_id"] ?>">
                                                <button class="accordion-button collapsed py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-collapse-banish-<?= $aUser["_id"] ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    Bannir
                                                </button>
                                            </h2>
                                            <div id="accordion-collapse-banish-<?= $aUser["_id"] ?>" class="accordion-collapse collapse" aria-labelledby="accordion-trigger-banish-<?= $aUser["_id"] ?>" data-bs-parent="#accordion-<?= $aUser["_id"] ?>">
                                                <div class="accordion-body">
                                                    <form action="<?= RouterDictionnary::buildURL("ManageUsers", ["bannir", $aUser["_id"]]) ?>" method="post">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="reason-<?= $nIndex ?>">Raison(s)</label>
                                                            <textarea class="form-control <?= FrontForm::printFieldClass("reason-" . $nIndex) ?>" name="reason-<?= $nIndex ?>" id="reason-<?= $nIndex ?>" cols="30" rows="2"><?= FrontForm::putFieldValue("reason-" . $nIndex) ?></textarea>
                                                            <?php FrontForm::printFieldErrors("reason-" . $nIndex) ?>
                                                        </div>
                                                        <div>
                                                            <input type="hidden" name="form-id" value="<?= $nIndex ?>">
                                                            <input class="btn btn-sm btn-danger" type="submit" value="Bannir <?= $aUser["pseudo"] ?>">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (Session::userGetRole() > 1) : ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="accordion-trigger-role-<?= $aUser["_id"] ?>">
                                                    <button class="accordion-button collapsed py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-collapse-role-<?= $aUser["_id"] ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Modifier le rôle
                                                    </button>
                                                </h2>
                                                <div id="accordion-collapse-role-<?= $aUser["_id"] ?>" class="accordion-collapse collapse" aria-labelledby="accordion-trigger-role-<?= $aUser["_id"] ?>" data-bs-parent="#accordion-<?= $aUser["_id"] ?>">
                                                    <div class="accordion-body">
                                                        <form action="<?= RouterDictionnary::buildURL("ManageUsers", ["role", $aUser["_id"]]) ?>" method="post">
                                                            <div class="mb-3">
                                                                <select class="form-select <?= FrontForm::printFieldClass("role-" . $nIndex) ?>" name="role-<?= $nIndex ?>" id="role-<?= $nIndex ?>">
                                                                    <?php foreach (Constants::USER_ROLES as $nValue => $aInfos) : ?>
                                                                        <option value="<?= $nValue ?>" <?= FrontForm::putSelectFieldValueFromData($aUser["role"], $nValue) ?> <?= (Session::userGetRole() <= $nValue ? "disabled" : "") ?>><?= $aInfos["name"] ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                                <?php FrontForm::printFieldErrors("role-" . $nIndex) ?>
                                                            </div>
                                                            <div>
                                                                <input type="hidden" name="form-id" value="<?= $nIndex ?>">
                                                                <input class="btn btn-sm btn-primary" type="submit" value="Changer le rôle de <?= $aUser["pseudo"] ?>">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php if ($nIndex < $nUsersCount - 1) : ?>
                        <hr>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php endif; ?>

            <nav>
                <ul class='pagination justify-content-center mt-3 mb-0'>
                    <?php FrontPagination::printSearchPagination(FrontData::$data->get("user")) ?>
                </ul>
            </nav>

        <?php endif; ?>

    </div>

</div>