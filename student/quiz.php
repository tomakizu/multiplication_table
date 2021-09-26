<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
}

$redirect = false;

require('../common/conn.php');

if (isSet($_POST['submit'])) {
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
                'number1' => $record['number1'],
                'number2' => $record['number2'],
                'answer'  => $record['number1'] * $record['number2']
            );
        }
    }

    $_SESSION['question'] = $questions;
}
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
    <h1>Quiz</h1>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <?php foreach ($_SESSION['question'] as $question) { ?>
            <p><?= $question['number1'] ?> * <?= $question['number2'] ?> = <input type="number" name="response[]" /></p>
        <?php } ?>

        <input type="submit" name="submit" value="Submit" />
    </form>
</body>
<script type="text/javascript">
    <?php
    if ($redirect) echo "location.replace('index.php')";
    ?>
</script>

</html>
<? $conn->close(); ?>