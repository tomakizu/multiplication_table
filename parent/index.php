<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'parent') {
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
            <h1><?= $language_string[$_SESSION['lang']]['greeting'] ?>, <?= $_SESSION['user']['name'] ?><?= $language_string[$_SESSION['lang']]['parent'] ?>!</h1>
            <p>
                <?php
                $sql = $conn->prepare('SELECT * FROM user INNER JOIN parent_relationship ON parent_relationship.child_id=user.id WHERE parent_relationship.parent_id=?');
                $sql->bind_param('i', $_SESSION['user']['id']);
                $sql->execute();

                $result = $sql->get_result();
                while ($record = $result->fetch_assoc()) {
                ?>
                    <a href="result.php?id=<?= $record['id'] ?>"><button type="button"><?= $language_string[$_SESSION['lang']]['view'] ?><?= $record['name'] ?><?= $language_string[$_SESSION['lang']]['answering_history'] ?></button></a>
                <?php } ?>
                <a href="statistic.php"><button type="button"><?= $language_string[$_SESSION['lang']]['view_statistic'] ?></button></a>
            </p>
        </div>

    </div>
</body>

<?php include('../common/js.php'); ?>

</html>