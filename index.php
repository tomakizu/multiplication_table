<?php
require('common/conn.php');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$alert_msg = '';
$redirect = false;
$redirect_url = '';

if (isset($_POST['submit'])) {
    $hash = hash('md5', $password);

    $sql = $conn->prepare('SELECT username, password, type_id, type_name FROM user INNER JOIN user_type ON user_type.id=user.type_id WHERE username=? AND password=?');
    $sql->bind_param('ss', $username, $hash);
    $sql->execute();

    $result = $sql->get_result();
    if ($record = $result->fetch_assoc()) {
        $redirect = true;
        $redirect_url = $record['type_name'];
    } else $alert_msg .= 'Invalid Login Information!';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="text" placeholder="Username" name="username" required />
        <input type="password" placeholder="Password" name="password" required />
        <input type="submit" name="submit" value="Login" />
    </form>
</body>
<script type="text/javascript">
    <?php
    if ($alert_msg <> '') echo "alert('$alert_msg');";
    if ($redirect) echo "location.replace('$redirect_url')";
    ?>
</script>

</html>
<?php $conn->close(); ?>