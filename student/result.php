<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');

$sql = $conn->prepare('SELECT * FROM quiz_result WHERE user_id=?');
$sql->bind_param('i', $_SESSION['user']['id']);
$sql->execute();
$result = $sql->get_result();
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
            <h1><?= $_SESSION['user']['name'] ?><?= $language_string[$_SESSION['lang']]['answering_history'] ?></h1>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['quiz_submit_time'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['score'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['details'] ?></th>
                </tr>
                <?php while ($record = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $record['datetime'] ?></td>
                        <td>
                            <?php
                            $score_sql = $conn->prepare('SELECT count(*) AS score FROM quiz_details WHERE quiz_id=? AND marks=1');
                            $score_sql->bind_param('i', $record['id']);
                            $score_sql->execute();
                            $score_result = $score_sql->get_result();
                            if ($score_record = $score_result->fetch_assoc()) {
                                echo $score_record['score'];
                            }
                            ?>
                        </td>
                        <td><a href="result_details.php?id=<?= $record['id'] ?>"><button type="button"><?= $language_string[$_SESSION['lang']]['show_details'] ?></button></a></td>
                    </tr>
                <?php } ?>
            </table>
            <p>
                <a href="index.php"><button type="button"><?= $language_string[$_SESSION['lang']]['back'] ?></button></a>
            </p>
        </div>
    </div>

</body>

<?php include('../common/js.php'); ?>

</html>
<?php $conn->close(); ?>