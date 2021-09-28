<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'teacher') {
    header('Location: ../');
}
require('../lang.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('head.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>
    <div class="container">
        <div class="text-center mt-5">
            <h1><?= $language_string[$_SESSION['lang']]['multiplication_table'] ?></h1>
            <div class="container">
                <div class="row align-self-center">
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <div class="col align-self-center border">
                            <?php for ($j = 1; $j <= 10; $j++) { ?>
                                <div class="row row-cols-auto">
                                    <div clas="col"><?= $i ?>*<?= $j ?>=<?= $i * $j ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <p>
                <a href="index.php"><button type="button"><?= $language_string[$_SESSION['lang']]['back'] ?></button></a>
            </p>
        </div>
    </div>
</body>

<?php include('../common/js.php'); ?>

</html>