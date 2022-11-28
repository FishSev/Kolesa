<?php
$autoloadPath = __DIR__ . '/vendor/autoload.php';
require_once $autoloadPath;

use cat\CatPictureService as Cat;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Страничка с котиками</title>
    <link rel="stylesheet" href="src\style.css">
</head>

<body>

    Здесь показывают котиков.<br>
    Категорию можно выбрать из списка добавив <b>?category_ids=</b><br>

    <?php
    $cat = new Cat();
    $cat->initializeClient();
    $cat->outputCategoryList();
    $cat->outputCat();
    ?>

</body>

</html>