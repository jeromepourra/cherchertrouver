<?php
$aConversation = FrontData::$data->get("conversation");
$sMyId = Session::userGetId();
$sMyPseudo = Session::userGetPseudo();
$sWithPseudo = FrontData::$data->get("conversation", "with-user", "pseudo");
?>

<div class="card m-auto" style="max-width: 750px;">

    <div class="card-header">
        <p class="mb-0">Avec : <a href="<?= RouterDictionnary::buildURL("AnnonceUser", [$aConversation["with-user"]["_id"]]) ?>"><?= $aConversation["with-user"]["pseudo"] ?></a></p>
        <p class="mb-0">Annonce : <a href="<?= RouterDictionnary::buildURL("Annonce", [$aConversation["annonce"]["_id"]]) ?>"><?= $aConversation["annonce"]["title"] . " : " . NumberFormat::format($aConversation["annonce"]["price"]) . " €" ?></a></p>
    </div>

    <div class="card-body">
        <ul class="list-group">
            <?php foreach ($aConversation["messages"] as $nIndex => $aMessage) : ?>
                <?php
                $bOwner = $aMessage["user_id"] == $sMyId;
                $bUnreaded = !$bOwner && $aMessage["unread"];
                $sGroupColor = $nIndex % 2 == 0 ? "text-bg-light" : "";
                ?>
                <li class="list-group-item p-3 <?= $sGroupColor ?> <?= $bUnreaded ? "position-relative" : "" ?>">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 order-sm-2">
                            <small class="d-block text-sm-end text-muted fs-14"><?= DateFormat::format($aConversation["conversation"]["last_message_date"]) ?></small>
                        </div>
                        <div class="col-12 col-sm-6 order-sm-1">
                            <?php if ($bOwner) : ?>
                                <small class="d-block text-muted">
                                    Par : <?= $sMyPseudo ?>
                                </small>
                            <?php else : ?>
                                <small class="d-block text-muted">
                                    Par : <?= $sWithPseudo ?>
                                    <?php if ($bUnreaded) : ?>
                                        <span class="badge bg-danger">Non lu</span>
                                    <?php endif; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="mb-0"><?= $aMessage["content"] ?></p>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item p-3">
                <form action="<?= RouterDictionnary::buildURL("Conversation", [$aConversation["conversation"]["_id"]]) ?>" method="post">
                    <div class="mb-3">
                        <?php FrontForm::printFormError("form") ?>
                        <?php FrontForm::printFormSuccess("form") ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="content">Envoyer un message</label>
                        <textarea class="form-control <?= FrontForm::printFieldClass("content") ?>" name="content" id="content" cols="30" rows="4"><?= FrontForm::putFieldValue("content") ?></textarea>
                        <?php FrontForm::printFieldErrors("content") ?>
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Envoyer">
                        <a class="btn btn-danger" href="<?= RouterDictionnary::buildURL("MyConversations") ?>">Annuler</a>
                    </div>
                </form>
            </li>
        </ul>

    </div>

    <div class="card-footer text-center text-bg-primary">
        <p class="m-0"><a class="link-light text-decoration-none" href="<?= RouterDictionnary::buildURL("MyConversations") ?>">Retour aux messages</a></p>
    </div>

</div>