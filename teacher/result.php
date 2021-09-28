<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'teacher') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');

$class = '';

if (isset($_GET['id'])) {
    $sql = $conn->prepare('SELECT *, quiz_result.id AS quiz_id FROM quiz_result INNER JOIN user ON quiz_result.user_id=user.id INNER JOIN class ON class.id=user.class_id WHERE class_id=?');
    $sql->bind_param('i', $_GET['id']);

    $class = $conn->prepare('SELECT * FROM class WHERE id=?');
    $class->bind_param('i', $_GET['id']);
    $class->execute();
    $class_result = $class->get_result();
    if ($class_record = $class_result->fetch_assoc()) {
        $class = $class_record['class_name'];
    }
} else {
    $sql = $conn->prepare('SELECT *, quiz_result.id AS quiz_id FROM quiz_result INNER JOIN user ON quiz_result.user_id=user.id INNER JOIN class ON class.id=user.class_id');
}
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
            <?php if (!isset($_GET['id'])) { ?>
                <h1><?= $language_string[$_SESSION['lang']]['answering_history_2'] ?></h1>
                <p>
                    <?php
                    $button_sql = $conn->prepare('SELECT * FROM class');
                    $button_sql->execute();

                    $button_result = $button_sql->get_result();
                    while ($button_record = $button_result->fetch_assoc()) {
                    ?>
                        <a href="result.php?id=<?= $button_record['id'] ?>"><button type="button"><?= $language_string[$_SESSION['lang']]['view'] ?><?= $button_record['class_name'] ?><?= $language_string[$_SESSION['lang']]['answering_history'] ?></button></a>
                    <?php } ?>
                </p>
            <?php } else { ?>
                <h1><?= $class . $language_string[$_SESSION['lang']]['answering_history'] ?></h1>
            <?php } ?>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <?php if (!isset($_GET['id'])) { ?><th scope="col"><?= $language_string[$_SESSION['lang']]['class'] ?></th> <?php } ?>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['student_name'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['quiz_submit_time'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['score'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['details'] ?></th>
                </tr>
                <?php while ($record = $result->fetch_assoc()) { ?>
                    <tr>
                        <?php if (!isset($_GET['id'])) { ?><td scope="col"><?= $record['class_name'] ?></th><?php } ?>
                            <td scope="col"><?= $record['name'] ?></th>
                            <td><?= $record['datetime'] ?></td>
                            <td>
                                <?php
                                $score_sql = $conn->prepare('SELECT count(*) AS score FROM quiz_details WHERE quiz_id=? AND marks=1');
                                $score_sql->bind_param('i', $record['quiz_id']);
                                $score_sql->execute();
                                $score_result = $score_sql->get_result();
                                if ($score_record = $score_result->fetch_assoc()) {
                                    echo $score_record['score'];
                                }
                                ?>
                            </td>
                            <td><a href="result_details.php?id=<?= $record['quiz_id'] ?>"><button type="button"><?= $language_string[$_SESSION['lang']]['show_details'] ?></button></a></td>
                    </tr>
                <?php } ?>
            </table>
            <p>
                <a href="<?= isset($_GET['id']) ? 'result' : 'index' ?>.php"><button type="button"><?= $language_string[$_SESSION['lang']]['back'] ?></button></a>
            </p>
        </div>
    </div>

</body>

<?php include('../common/js.php'); ?>

</html>
<?php $conn->close(); ?>