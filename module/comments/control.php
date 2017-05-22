<?php
Uaccess(2);
global $Opt;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);

    if (isset($Param['action']) and $Param['action'] == 'delete')
    {
        $PdoQuery = "DELETE FROM `comments` WHERE `id` = $Param[id]";
        $PdoExec = $Pdo->exec($PdoQuery);
        MessageSend(3, 'comment deleted.');
    }

    else if (isset($Param['action']) and $Param['action'] == 'edit')
    {
        $_SESSION['COMMENTS_EDIT'] = $Param['id'];
        exit(header('location: ' . $_SERVER['HTTP_REFERER']));
    }

    else if (isset($_POST['save']))
    {
        $PdoQuery  = "UPDATE `comments` SET `text` = :text WHERE `id` = :ID";
        $Text = $_POST['text'];
        $Id = $_SESSION['COMMENTS_EDIT'];
        $Upd = $Pdo->prepare($PdoQuery);
        $Upd ->bindValue(":text",$Text);
        $Upd ->bindValue(":ID",$Id);
        $Upd ->execute();

        unset($_SESSION['COMMENTS_EDIT']);
        MessageSend(3, 'Comment edited.');
    }

    else if ($_POST['cancel'])
    {
        unset($_SESSION['COMMENTS_EDIT']);
        MessageSend(3, 'Edit canceled.');
    }


?>