<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'parent') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');
if (!isset($_GET['id'])) {
    header('Location: ./result.php');
}

$submission_time = '';
$total_score = 0;

$sql = $conn->prepare('SELECT * FROM quiz_details INNER JOIN quiz_result ON quiz_result.id=quiz_details.quiz_id INNER JOIN question ON question.id=quiz_details.question_id INNER JOIN parent_relationship ON parent_relationship.child_id = quiz_result.user_id WHERE quiz_id=? AND parent_id=?');
$sql->bind_param('ii', $_GET['id'], $_SESSION['user']['id']);
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
            <table class="table table-bordered">
                <tr class="table-secondary">
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['question'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['answer'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['student_response'] ?></th>
                    <th scope="col"><?= $language_string[$_SESSION['lang']]['score'] ?></th>
                </tr>
                <?php while ($record = $result->fetch_assoc()) { ?>
                    <tr class="table-<?= $record['marks'] == 0 ? 'danger' : 'success' ?>">
                        <td><?= $record['number1'] ?> * <?= $record['number2'] ?></td>
                        <td><?= $record['number1'] * $record['number2'] ?></td>
                        <td><?= $record['response'] ?></td>
                        <td><?= $record['marks'] ?></td>
                    </tr>
                    <?php $submission_time = $record['datetime']; ?>
                    <?php $total_score += $record['marks']; ?>
                <?php } ?>
            </table>
            <table class="table table-bordered">
                <tr>
                    <th scope="col" class="table-secondary text-end"><?= $language_string[$_SESSION['lang']]['quiz_submit_time'] ?>:</th>
                    <td scope="col"><?= $submission_time ?></th>
                </tr>
                <tr>
                    <th scope="col" class="table-secondary text-end"><?= $language_string[$_SESSION['lang']]['total_score'] ?>:</th>
                    <td scope="col"><?= $total_score ?></th>
                </tr>
            </table>

            <p>
                <a href="result.php"><button type="button"><?= $language_string[$_SESSION['lang']]['back'] ?></button></a>
            </p>
        </div>
    </div>
</body>

<?php include('../common/js.php'); ?>

</html>
<?php $conn->close(); ?>