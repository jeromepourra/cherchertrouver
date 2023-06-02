<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= (!empty(FrontData::$data->get("head-desc")) ? FrontData::$data->get("head-desc") : Constants::WEB_SITE_DEFAULT_DESC) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= WEB_ROOT . "/views/public/css/style.css" ?>">
    <title>
        <?= FrontData::$data->get("head-title") ?>
    </title>
</head>

<body>

    <div id="page-container">

        <header>
            <?php require ROOT . "/views/parts/header.php" ?>
        </header>

        <main class="container-fluid flex-grow-1 d-flex flex-column justify-content-center px-3" style="background-image: url(<?= WEB_ROOT . "/views/public/img/background.png" ?>);">
            <div class="my-5">
                <?php echo ($___VIEW_CONTROLLER___); ?>
            </div>
        </main>

        <footer>
            <?php require ROOT . "/views/parts/footer.php" ?>
        </footer>

        <?php FrontModal::printModal() ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <?php FrontScript::printScripts() ?>
    <?php FrontModal::showModal() ?>

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

</body>

</html>