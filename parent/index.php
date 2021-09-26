<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'parent') {
    header('Location: ../');
}

if (isset($_POST['logout'])) {
    header('Location: ../logout.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Multiplication Table - Parent Portal</title>
</head>

<body>
    <h1>Hi, <?= $_SESSION['user']['name'] ?>!</h1>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="submit" name="logout" value="Logout" />
    </form>
</body>

</html>