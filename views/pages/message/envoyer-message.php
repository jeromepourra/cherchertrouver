<?php
$aUser = FrontData::$data->get("conversation", "user");
$aAnnonce = FrontData::$data->get("conversation", "annonce");
?>

<div class="card m-auto" style="max-width: 750px;">
    <div class="card-header text-center text-bg-primary">
        <h1 class="fs-5 fw-bold mb-0">
            <?= FrontData::$data->get("page-title") ?>
        </h1>
    </div>
    <div class="card-body">
        <form action="<?= RouterDictionnary::buildURL("ConversationCreate", [$aAnnonce["_id"]]) ?>" method="post">
            <div>
                <?php FrontForm::printFormError("form") ?>
                <?php FrontForm::printFormSuccess("form") ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="receiver">Destinataire</label>
                <input class="form-control <?= FrontForm::printFieldClass("receiver") ?>" type="text" id="receiver" name="receiver" readonly value="<?= $aUser["pseudo"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="subject">Annonce</label>
                <input class="form-control <?= FrontForm::printFieldClass("subject") ?>" type="text" id="subject" name="subject" readonly value="<?= $aAnnonce["title"] . " " . NumberFormat::format($aAnnonce["price"]) . " €" ?>">
            </div>
            <div class="mb-3">
                <label class="form-label" for="content">Message</label>
                <textarea class="form-control <?= FrontForm::printFieldClass("content") ?>" name="content" id="content" cols="30" rows="4"><?= FrontForm::putFieldValue("content") ?></textarea>
                <?php FrontForm::printFieldErrors("content") ?>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Envoyer">
                <a class="btn btn-danger" href="<?= Router::getReferer() ?>">Annuler</a>
            </div>
        </form>
    </div>
</div>