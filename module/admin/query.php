<?php
$pdo = new PDO($dsn, USER, PASS, $opt);

if ($Param['com_delete']) {
    $query = "DELETE FROM `comments` WHERE `id` = $Param[com_delete]";
    $pdo->exec($query);
    MessageSend(3, 'Comment deleted.');
} else if ($_POST['change_group']) {

    $query = "UPDATE `users` SET `group` = :group WHERE `login` = :login";
    $group = $_POST['group'];
    $login = $_POST['login'];
    $upd = $pdo->prepare($query);
    $upd ->bindValue(":group",$group);
    $upd ->bindValue(":login",$login);
    $upd ->execute();


    MessageSend(3, 'User group <b>' . $_POST['login'] . '</b> сhange.');
} else if ($Param['logout']) {
    unset($_SESSION['ADMIN_LOGIN_IN']);
    MessageSend(3, 'Admin session ended.', '/');
} else MessageSend(1, 'Error.', '/admin');
?>