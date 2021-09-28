<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'parent') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');

$student_name = '';

if (isset($_GET['id'])) {
    $sql = $conn->prepare('SELECT *, quiz_result.id AS quiz_id FROM quiz_result INNER JOIN parent_relationship ON parent_relationship.child_id=quiz_result.user_id WHERE user_id=? AND parent_id=?');
    $sql->bind_param('ii', $_GET['id'], $_SESSION['user']['id']);

    $name_sql = $conn->prepare('SELECT * FROM user INNER JOIN parent_relationship ON parent_relationship.child_id=user.id WHERE id=? AND parent_id=?');
    $name_sql->bind_param('ii', $_GET['id'], $_SESSION['user']['id']);
    $name_sql->execute();
    $name_result = $name_sql->get_result();
    if ($name_record = $name_result->fetch_assoc()) {
        $student_name = $name_record['name'];
    }
} else {
    $sql = $conn->prepare('SELECT *, quiz_result.id AS quiz_id FROM quiz_result INNER JOIN parent_relationship ON parent_relationship.child_id=quiz_result.user_id INNER JOIN user ON user.id=quiz_result.user_id WHERE parent_id=?');
    $sql->bind_param('i', $_SESSION['user']['id']);
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
                    $button_sql = $conn->prepare('SELECT * FROM user INNER JOIN parent_relationship ON parent_relationship.child_id=user.id WHERE parent_relationship.parent_id=?');
                    $button_sql->bind_param('i', $_SESSION['user']['id']);
                    $button_sql->execute();

                    $button_result = $button_sql->get_result();
                    while ($button_record = $button_result->fetch_assoc()) {
                    ?>
                        <a href="result.php?id=<?= $button_record['id'] ?>"><button type="button"><?= $language_string[$_SESSION['lang']]['view'] ?><?= $button_record['name'] ?><?= $language_string[$_SESSION['lang']]['answering_history'] ?></button></a>
                    <?php } ?>
                </p>
            <?php } else { ?>
                <h1><?= $student_name . $language_string[$_SESSION['lang']]['answering_history'] ?></h1>
            <?php } ?>
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <?php if (!isset($_GET['id'])) { ?><th scope="col"><?= $language_string[$_SESSION['lang']]['student_name'] ?></th> <?php } ?>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['quiz_submit_time'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['score'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['details'] ?></th>
                </tr>
                <?php while ($record = $result->fetch_assoc()) { ?>
                    <tr>
                        <?php if (!isset($_GET['id'])) { ?><td scope="col"><?= $record['name'] ?></th> <?php } ?>
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