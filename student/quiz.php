<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
}

$redirect = false;
$quiz_id = 0;

require('../lang.php');
require('../common/conn.php');

if (isset($_POST['submit'])) {
    $sql = $conn->prepare('INSERT INTO quiz_result (user_id) VALUES (?)');
    $sql->bind_param('i', $_SESSION['user']['id']);

    $sql->execute();
    $quiz_id = $conn->insert_id;

    for ($i = 0; $i < count($_POST['response']); $i++) {
        $marks = $_POST['response'][$i] == $_SESSION['question'][$i]['answer'] ? 1 : 0;
        $sql = $conn->prepare('INSERT INTO quiz_details (quiz_id, question_id, response, marks) VALUES (?, ?, ?, ?)');
        $sql->bind_param('iiii', $quiz_id, $_SESSION['question'][$i]['question_id'], $_POST['response'][$i], $marks);
        $sql->execute();
    }
    unset($_SESSION['question']);
    $redirect = true;
} elseif (!isset($_SESSION['question'])) {
    $numbers = range(1, 90);
    shuffle($numbers);

    $numbers = array_slice($numbers, 0, 10);
    $questions = array();

    foreach ($numbers as $id) {
        $sql = $conn->prepare('SELECT * FROM question WHERE id=?');
        $sql->bind_param('i', $id);
        $sql->execute();

        $result = $sql->get_result();
        if ($record = $result->fetch_assoc()) {
            $questions[] = array(
                'question_id' => $record['id'],
                'number1'     => $record['number1'],
                'number2'     => $record['number2'],
                'answer'      => $record['number1'] * $record['number2']
            );
        }
    }

    $_SESSION['question'] = $questions;
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
            <h1><?= $language_string[$_SESSION['lang']]['quiz'] ?></h1>
            <p><?= $language_string[$_SESSION['lang']]['quiz_instruction'] ?></p>
            <div class="container">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div class="row gy-5 row-cols-2 align-self-center">
                        <?php foreach ($_SESSION['question'] as $question) { ?>
                            <div class="col"><?= $question['number1'] ?> * <?= $question['number2'] ?> = <input type="number" name="response[]" style="width: 50px;" required /></div>
                        <?php } ?>
                    </div>
                    <p><input type="submit" name="submit" value="<?= $language_string[$_SESSION['lang']]['submit'] ?>" /></p>
                </form>
            </div>
        </div>

    </div>
</body>

<?php include('../common/js.php'); ?>

<script type="text/javascript">
    <?php
    if ($redirect) echo "location.replace('result_details.php?id=$quiz_id')";
    ?>
</script>

</html>
<? $conn->close(); ?>