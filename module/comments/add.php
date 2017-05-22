<?php
ULogin(1);
global $Opt, $Param;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);

    if ($_POST['enter'] and $_POST['text'])
    {
        //xss protect
        $_POST['text'] = FormChars($_POST['text']);
        $ID = ModuleID ($Param['module']);

        if ($ID == 1) $Table = 'news';
        else if ($ID == 2) $Table = 'load';
        $Pdo = new PDO($Dsn, USER, PASS, $Opt);
        $Stmt = $Pdo->query('SELECT `id` FROM `'.$Table.'` WHERE `id` = '.$Param['id']);
        $Row=  $Stmt->fetch(PDO::FETCH_ASSOC);
        if (!$Row['id']) MessageSend(1, 'Material not found.', '/'.$Param['module']);

        $Id = '';
        $Material = $Param['id'];
        $Module = $ID;
        $Added = $_SESSION['USER_LOGIN'];
        $Text = $_POST['text'];
        $Date  = date("Y-m-d H:i:s");

        $PdoQuery = "INSERT INTO comments (`id`,`material`,`module`,`added`,`text`, `date`) VALUES (:id,:material,:module,:added,:text, :date)";
        $PdoResult = $Pdo->prepare($PdoQuery);
        $PdoExec = $PdoResult->execute(array(":id"=>$Id,":material"=>$Material,":module"=>$Module,":added"=>$Added,":text"=>$Text, ":date"=>$Date));

        MessageSend(3, 'Comment added.', '/'.$Param['module'].'/material/id/'.$Param['id']);
    }
?>