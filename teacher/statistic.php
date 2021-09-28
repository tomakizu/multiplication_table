<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'teacher') {
    header('Location: ../');
}

require('../lang.php');
require('../common/conn.php');

$sql = $conn->prepare('SELECT * FROM class');
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
            <h1><?= $language_string[$_SESSION['lang']]['statistic'] ?></h1>
            <div class="container">
                <div class="row align-self-center">
                    <?php while ($record = $result->fetch_assoc()) { ?>
                        <div class="col align-self-center border">
                            <p><?= $record['class_name'] ?><?= $language_string[$_SESSION['lang']]['statistic_2'] ?></p>
                            <table class="table table-bordered">
                                <tr class="table-secondary">
                                    <th scope="col"><?= $language_string[$_SESSION['lang']]['multiplication_table'] ?></th>
                                    <th scope="col"><?= $language_string[$_SESSION['lang']]['accuracy'] ?></th>
                                </tr>
                                <?php
                                for ($i = 2; $i <= 10; $i++) {
                                    $statement = 'SELECT';
                                    $statement .= '( SELECT COUNT(*) FROM quiz_details INNER JOIN quiz_result ON quiz_details.quiz_id = quiz_result.id INNER JOIN question ON quiz_details.question_id = question.id INNER JOIN user ON user.id=quiz_result.user_id WHERE class_id=? AND number1=? AND marks=1 ) AS correct_count, ';
                                    $statement .= '( SELECT COUNT(*) FROM quiz_details INNER JOIN quiz_result ON quiz_details.quiz_id = quiz_result.id INNER JOIN question ON quiz_details.question_id = question.id INNER JOIN user ON user.id=quiz_result.user_id WHERE class_id=? AND number1=?) AS total_count';
                                    $sub_sql = $conn->prepare($statement);
                                    $sub_sql->bind_param('iiii', $record['id'], $i, $record['id'], $i);
                                    $sub_sql->execute();
                                    $sub_result = $sub_sql->get_result();

                                    if ($sub_record = $sub_result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td scope="col"><?= $i ?></td>
                                            <?php if ($sub_record['total_count'] == 0) { ?>
                                                <td scope="col"><?= $language_string[$_SESSION['lang']]['no_data'] ?></td>
                                            <?php } else { ?>
                                                <?php $is_low = round($sub_record['correct_count'] / $sub_record['total_count'] * 1000) / 10 < 60; ?>
                                                <td scope="col"<?=$is_low ? ' class="table-danger"' : '' ?>><?= round($sub_record['correct_count'] / $sub_record['total_count'] * 1000) / 10 . '%' ?></td>
                                            <?php } ?>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <br>
            <p>
                <a href="index.php"><button type="button"><?= $language_string[$_SESSION['lang']]['back'] ?></button></a>
            </p>
        </div>

    </div>
</body>

<?php include('../common/js.php'); ?>

</html>