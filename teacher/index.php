<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'teacher') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

if (isset($_POST['logout'])) {
    header('Location: ../logout.php');
}
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
            <h1><?= $language_string[$_SESSION['lang']]['greeting'] ?>, <?= $_SESSION['user']['name'] ?>!</h1>
            <p>
                <a href="table.php"><button type="button"><?= $language_string[$_SESSION['lang']]['show_m_table'] ?></button></a>
                <a href="result.php"><button type="button"><?= $language_string[$_SESSION['lang']]['view_history'] ?></button></a>
                <a href="statistic.php"><button type="button"><?= $language_string[$_SESSION['lang']]['view_statistic'] ?></button></a>
            </p>
        </div>

    </div>
</body>

<?php include('../common/js.php'); ?>

</html>