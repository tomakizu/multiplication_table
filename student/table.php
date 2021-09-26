<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'student') {
    header('Location: ../');
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
    <h1>Multiplication Table</h1>
    <table>
        <tr>
            <?php for ($i = 1; $i <= 10; $i++) { ?>
                <td>
                    <?php for ($j = 1; $j <= 10; $j++) { ?>
                        <p><?= $i ?> * <?= $j ?> = <?= $i * $j ?></p>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
    </table>
    <p>
        <a href="index.php"><button type="button">Back</button></a>
    </p>
</body>

</html>