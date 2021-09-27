<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
}

require('../common/conn.php');

$sql = $conn->prepare('SELECT * FROM quiz_result WHERE user_id=?');
$sql->bind_param('i', $_SESSION['user']['id']);
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
    <h1>Result for <?= $_SESSION['user']['name'] ?></h1>
    <table border="1">
        <tr>
            <th>Quiz Finish Time</th>
            <th>Score</th>
            <th>Details</th>
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
                <td><a href="result_details.php?id=<?= $record['id'] ?>"><button type="button">Show Details</button></a></td>
            </tr>
        <?php } ?>
    </table>
    <p>
        <a href="index.php"><button type="button">Back</button></a>
    </p>
</body>

</html>
<?php $conn->close(); ?>