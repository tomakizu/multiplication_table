<?php
session_start();
if (!isSet($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
} else if ($_SESSION['lang'] == 'en') {
    $_SESSION['lang'] = 'zh';
} else {
    $_SESSION['lang'] = 'en';
}

if (isSet($_GET['target']) && isSet($_SESSION['user'])) {
    header('Location: ' . $_SESSION['user']['type'] . '/' . $_GET['target']);
} else {
    header('Location: index.php');
}
