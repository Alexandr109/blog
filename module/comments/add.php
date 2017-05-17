<?php
ULogin(1);
$pdo = new PDO($dsn, USER, PASS, $opt);

if ($_POST['enter'] and $_POST['text']) {
    $_POST['text'] = FormChars($_POST['text']);
    $ID = ModuleID($Param['module']);
    if ($ID == 1) $Table = 'news';
    else if ($ID == 2) $Table = 'load';
    $pdo = new PDO($dsn, USER, PASS, $opt);
    $stmt = $pdo->query('SELECT `id` FROM `'.$Table.'` WHERE `id` = '.$Param['id']);
    $Row=  $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$Row['id']) MessageSend(1, 'Material not found.', '/'.$Param['module']);
    $id = '';
    $material = $Param[id];
    $module = $ID;
    $added = $_SESSION[USER_LOGIN];
    $text = $_POST[text];
    $date  = date("Y-m-d H:i:s");

    $pdoQuery = "INSERT INTO comments (`id`,`material`,`module`,`added`,`text`, `date`) VALUES (:id,:material,:module,:added,:text, :date)";
    $pdoResult = $pdo->prepare($pdoQuery);
    $pdoExec = $pdoResult->execute(array(":id"=>$id,":material"=>$material,":module"=>$module,":added"=>$added,":text"=>$text, ":date"=>$date));

    MessageSend(3, 'Comment added.', '/'.$Param['module'].'/material/id/'.$Param['id']);
}
?>