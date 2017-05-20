<?php
UAccess(2);
$pdo = new PDO($dsn, USER, PASS, $opt);


if ($Param['id'] and $Param['command'])
{

    if ($Param['command'] == 'delete')
    {
        $query = "DELETE FROM `news` WHERE `id` = $Param[id]";
        $query1 = "DELETE FROM `comments` WHERE `material` = $Param[id] AND `module` = 1";
        $pdo->exec($query);
        $pdo->exec($query1);
        MessageSend(3, 'News is deleted.', '/news');
    }


}
?>