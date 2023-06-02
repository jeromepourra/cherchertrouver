<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= (!empty(FrontData::$data->get("head-desc")) ? FrontData::$data->get("head-desc") : Constants::WEB_SITE_DEFAULT_DESC) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= WEB_ROOT . "/views/public/css/style.css" ?>">
    <title>
        <?= FrontData::$layoutData->get("head-title") ?>
    </title>
</head>

<body>

    <div id="page-container">

        <header>
            <?php require ROOT . "/views/parts/header.php" ?>
        </header>

        <main class="container-fluid flex-grow-1 d-flex flex-column p-0">
            <?php echo ($___VIEW_CONTROLLER___); ?>
        </main>

        <footer>
            <?php require ROOT . "/views/parts/footer.php" ?>
        </footer>

        <?php FrontModal::printModal() ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>

    <?php FrontModal::showModal() ?>

</body>

</html>