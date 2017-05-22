<?php
UAccess(2);
global $Opt;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);


if ($Param['id'] and $Param['command'])
{

    if ($Param['command'] == 'delete')
    {   //delete news
        $Query = "DELETE FROM `news` WHERE `id` = $Param[id]";
        //delete comments
        $Query1 = "DELETE FROM `comments` WHERE `material` = $Param[id] AND `module` = 1";
        $Pdo->exec($Query);
        $Pdo->exec($Query1);
        MessageSend(3, 'News is deleted.', '/news');
    }


}
?>