<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Mi Agenda' ?></title>
    <link href="<?= BASE_URL ?>Estilo2.css" rel="stylesheet">
    <?= isset($additionalCSS) ? $additionalCSS : '' ?>
</head>
<body>
    <?= $content ?>
    <?= isset($additionalJS) ? $additionalJS : '' ?>
</body>
</html>
