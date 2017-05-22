<?php
global $Opt;

$Pdo = new PDO($Dsn, USER, PASS, $Opt);

if (isset($Param['com_delete']))
{
    $Query = "DELETE FROM `comments` WHERE `id` = $Param[com_delete]";
    $Pdo->exec($Query);
    MessageSend(3, 'Comment deleted.');
}
else if (isset($_POST['change_group']))
{
    $Query = "UPDATE `users` SET `group` = :group WHERE `login` = :login";
    $Group = $_POST['group'];
    $Login = $_POST['login'];
    $Upd = $Pdo->prepare($Query);
    $Upd ->bindValue(":group",$Group);
    $Upd ->bindValue(":login",$Login);
    $Upd ->execute();
    MessageSend(3, 'User group <b>' . $_POST['login'] . '</b> сhange.');
}
else if ($Param['logout'])
{
    unset($_SESSION['ADMIN_LOGIN_IN']);
    MessageSend(3, 'Admin session ended.', '/');
}
else MessageSend(1, 'Error.', '/admin');

?>