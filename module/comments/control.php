<?php
Uaccess(2);
$pdo = new PDO($dsn, USER, PASS, $opt);

if ($Param['action'] == 'delete') {
   $query = "DELETE FROM `comments` WHERE `id` = $Param[id]";
    $pdo->exec($query);
    MessageSend(3, 'comment deleted.');

} else if ($Param['action'] == 'edit') {
    $_SESSION['COMMENTS_EDIT'] = $Param['id'];
    exit(header('location: ' . $_SERVER['HTTP_REFERER']));


} else if ($_POST['save']) {
    $query  = "UPDATE `comments` SET `text` = :text WHERE `id` = :ID";

    $text = $_POST['text'];
    $ID = $_SESSION['COMMENTS_EDIT'];
    $upd = $pdo->prepare($query);
    $upd ->bindValue(":text",$text);
    $upd ->bindValue(":ID",$ID);
    $upd ->execute();

    unset($_SESSION['COMMENTS_EDIT']);
    MessageSend(3, 'Comment edited.');


} else if ($_POST['cancel']) {
    unset($_SESSION['COMMENTS_EDIT']);
    MessageSend(3, 'Edit canceled.');
}


?>