<?php
$bConnected = Session::userConnected();
$sController = FrontData::$data->get("controller", "name");
$aLayoutData = FrontData::$layoutData->get();
$nUnread = FrontData::$layoutData->get("unread");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= RouterDictionnary::getURL() ?>"><?= Constants::WEB_SITE_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (!$bConnected) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= RouterDictionnary::getURL("Signin") ?>">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= RouterDictionnary::getURL("Signup") ?>">Inscription</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= RouterDictionnary::getURL("AnnoncePost") ?>">Déposer une annonce</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= Session::userGetPseudo() ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark p-0">
                            <li>
                                <a class="nav-link dropdown-item p-2" href="<?= RouterDictionnary::getURL("MyAccount") ?>">Mon compte</a>
                            </li>
                            <li>
                                <a class="nav-link dropdown-item p-2" href="<?= RouterDictionnary::getURL("AnnonceUser") . "/" . Session::userGetId() ?>">Mes annonces</a>
                            </li>
                            <li>
                                <a class="nav-link dropdown-item p-2 position-relative" href="<?= RouterDictionnary::getURL("MyConversations") ?>">
                                    Mes messages
                                    <?php if (isset($nUnread) && $nUnread > 0) : ?>
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?= $nUnread ?> nouv.</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider bg-secondary m-0">
                            </li>
                            <li>
                                <a class="nav-link dropdown-item p-2" href="<?= RouterDictionnary::getURL("Signout") ?>">Deconnexion</a>
                            </li>
                        </ul>
                    </li>
                    <?php if (Session::userGetRole() > 0) : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= Constants::USER_ROLES[Session::userGetRole()]["menu-name"] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark p-0">
                                <li>
                                    <a class="nav-link dropdown-item p-2" href="<?= RouterDictionnary::getURL("ManageAnnonces") ?>">Annonces</a>
                                </li>
                                <li>
                                    <a class="nav-link dropdown-item p-2" href="<?= RouterDictionnary::getURL("ManageUsers") ?>">Utilisateurs</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <form class="d-flex" action="<?= RouterDictionnary::buildURL("Search", ["page", 1]) ?>" role="search" method="get">
                <input class="form-control me-2" type="search" name="key-word" placeholder="Recherche rapide..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Go</button>
            </form>
        </div>
    </div>
</nav>