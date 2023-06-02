<?php
$aConversations = FrontData::$data->get("conversations");
?>

<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <div class="text-bg-dark rounded p-3 mb-3">
            <p>Consulter vos différentes discussions avec les usagers du site.</p>
            <p>Pour créer de nouvelles discussions : </p>
            <ul class="mb-0">
                <li>Consultez les différentes annonces <a href="<?= RouterDictionnary::buildURL() ?>">ici</a></li>
                <li>Cliquez sur les Détails d'une annonce</li>
                <li>Dans la section Contacter, cliquez sur Envoyer un message</li>
                <li>Enfin, envoyez un message à l'utilisateur</li>
            </ul>
        </div>
        <?php if (count($aConversations) > 0) : ?>
            <ul class="list-group">
                <?php foreach ($aConversations as $nIndex => $aConversation) : ?>
                    <?php
                    $sGroupColor = $nIndex % 2 == 0 ? "text-bg-light" : "";
                    ?>
                    <li class="list-group-item <?= $sGroupColor ?>">
                        <p class="mb-0">Avec : <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [$aConversation["with-user"]["_id"]]) ?>"><?= $aConversation["with-user"]["pseudo"] ?></a></p>
                        <p class="mb-2">Annonce : <a href="<?= RouterDictionnary::buildURL("Annonce", [$aConversation["annonce"]["_id"]]) ?>"><?= $aConversation["annonce"]["title"] . " " . NumberFormat::format($aConversation["annonce"]["price"]) . " €" ?></a></p>

                        <div class="row mb-2">
                            <div class="col-12 col-sm-8 mb-2 mb-sm-0">
                                <small class="d-block text-muted fs-14">Dernier message de <?= $aConversation["last-message-user"] ?></small>
                                <small class="d-block text-muted fs-14"><?= DateFormat::format($aConversation["conversation"]["last_message_date"]) ?></small>
                            </div>
                            <div class="col-12 col-sm-4 text-sm-end mt-auto">
                                <a class="btn btn-sm btn-primary position-relative px-3" href="<?= RouterDictionnary::buildURL("Conversation", [$aConversation["conversation"]["_id"]]) ?>">
                                    Lire
                                    <?php if ($aConversation["unread"]) : ?>
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?= $aConversation["unread"] ?> nouv.</span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        </div>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="m-0">Vous n'avez aucune conversation pour le moment</p>
        <?php endif; ?>
    </div>

    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::getURL("AnnonceUser") . "/" . Session::userGetId() ?>">Consulter mes annonces</a></p>
    </div>

</div>