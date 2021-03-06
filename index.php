<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: ' . $_SESSION['user']['type']);
}

require('common/conn.php');

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$alert_msg = '';
$redirect = false;
$redirect_url = '';

if (isset($_POST['submit'])) {
    $hash = hash('md5', $password);

    $sql = $conn->prepare('SELECT user.*, type_name FROM user INNER JOIN user_type ON user_type.id=user.type_id WHERE username=? AND password=?');
    $sql->bind_param('ss', $username, $hash);
    $sql->execute();

    $result = $sql->get_result();
    if ($record = $result->fetch_assoc()) {
        $redirect = true;
        $redirect_url = $record['type_name'];

        $_SESSION['user']['id'] = $record['id'];
        $_SESSION['user']['name'] = $record['name'];
        $_SESSION['user']['type'] = $record['type_name'];
        $_SESSION['lang'] = $_POST['language'];
    } else $alert_msg .= 'Invalid Login Information!';
}
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Learning Multiplication 快樂學乘法</title>

    <!-- Bootstrap core CSS -->
    <link href="common/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #e3e3e3;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>

<body class="text-center">

    <main class="form-signin">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <h3 class="fw-normal">Learning Multiplication</h3>
            <h3 class="fw-normal">快樂學乘法</h3>

            <div>
                <p>Username / 用戶名稱</p>
            </div>
            <div>
                <input type="text" class="form-control" name="username">
            </div>
            <br>
            <div>
                <p>Password / 密碼</p>
            </div>
            <div>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="checkbox mb-3">
                <label for="language">Language / 語言:</label>
                <select name="language">
                    <option value="en">English</option>
                    <option value="zh">中文</option>
                </select>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Sign in / 登入</button>
        </form>
    </main>



</body>
<script type="text/javascript">
    <?php
    if ($alert_msg <> '') echo "alert('$alert_msg');";
    if ($redirect) echo "location.replace('$redirect_url')";
    ?>
</script>

</html>
<?php $conn->close(); ?>