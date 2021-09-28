<?php
if (!isset($_SESSION['user'])) {
    header('Location: ../');
} elseif ($_SESSION['user']['type'] != 'teacher') {
    header('Location: ../');
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><?= $language_string[$_SESSION['lang']]['title'] ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" aria-current="page" href="index.php"><?= $language_string[$_SESSION['lang']]['home'] ?></a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'table.php' ? ' active' : '' ?>" href="table.php"><?= $language_string[$_SESSION['lang']]['multiplication_table'] ?></a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'result.php' ? ' active' : '' ?>" href="result.php"><?= $language_string[$_SESSION['lang']]['history'] ?></a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'statistic.php' ? ' active' : '' ?>" href="statistic.php"><?= $language_string[$_SESSION['lang']]['statistic'] ?></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= $_SESSION['user']['name'] ?></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../change_lang.php?target=<?= basename($_SERVER['PHP_SELF']) ?>"><?= $language_string[$_SESSION['lang']]['change_lang'] ?></a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="../logout.php"><?= $language_string[$_SESSION['lang']]['logout'] ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>