<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
}

require('../common/conn.php');
if (!isset($_GET['id'])) {
    header('Location: ./result.php');
}

$sql = $conn->prepare('SELECT * FROM quiz_details INNER JOIN quiz_result ON quiz_result.id=quiz_details.quiz_id INNER JOIN question ON question.id=quiz_details.question_id WHERE quiz_id=? AND user_id=?');
$sql->bind_param('ii', $_GET['id'], $_SESSION['user']['id']);
$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Multiplication Table - Student Portal</title>
</head>

<body>
    <?php while ($record = $result->fetch_assoc()) { ?>
        <p><?= $record['number1'] ?> * <?= $record['number2'] ?> = <input type="number" style="width: 50px;" value="<?= $record['response'] ?>" readonly /><?= $record['marks'] == 0 ? 'x ' . ($record['number1'] * $record['number2']) : '' ?></p>
    <?php } ?>
    <p>
        <a href="result.php"><button type="button">Back</button></a>
    </p>
</body>

</html>
<?php $conn->close(); ?>