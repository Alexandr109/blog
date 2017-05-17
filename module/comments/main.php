<?php
function COMMENTS()
{


    global $Module, $Page, $Param, $dsn, $opt;
    $pdo = new PDO($dsn, USER, PASS, $opt);
    if ($_SESSION['USER_LOGIN_IN'] != 1) echo '<br><br>Only registered users can coment.';
    else echo '<br><br><form method="POST" action="/comments/add/module/' . $Page . '/id/' . $Param['id'] . '">
<textarea class="ChatMessage" name="text" placeholder="message text" required></textarea>
<br><input type="submit" name="enter" value="Send"> <input type="reset" value="Clear">
</form>';

    $ID = ModuleID($Page);
    $stmt = $pdo->query('SELECT COUNT(`id`) FROM `comments` WHERE `module` = ' . $ID . ' AND `material` = ' . $Param['id']);
    $Count = $stmt->fetch(PDO::FETCH_NUM);


    if (!$Param['page']) {
        $Param['page'] = 1;
        $stmt = $pdo->query('SELECT `id`, `added`, `date`, `text` FROM `comments` WHERE `module` = ' . $ID . ' AND `material` = ' . $Param['id'] . ' ORDER BY `id` DESC LIMIT 0, 5');
        $Result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $Start = ($Param['page'] - 1) * 5;
        $stmt = $pdo->query(str_replace('START', $Start, 'SELECT `id`, `added`, `date`, `text` FROM `comments` WHERE `module` = ' . $ID . ' AND `material` = ' . $Param['id'] . ' ORDER BY `id` DESC LIMIT START, 5'));
        $Result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    PageSelector("/$Page/$Module/id/$Param[id]/page/", $Param['page'], $Count);

    foreach ($Result as $row => $Row) {
        if ($_SESSION['USER_GROUP'] == 2) $Admin = ' | <a href="/comments/control/action/delete/id/' . $Row['id'] . '" class="lol">Delete</a> | <a href="/comments/control/action/edit/id/' . $Row['id'] . '" class="lol">Edit</a>';
        if ($Row['id'] == $_SESSION['COMMENTS_EDIT']) $Row['text'] = '<form method="POST" action="/comments/control"><textarea class="ChatMessage" name="text" placeholder="Message text" required>' . $Row['text'] . '</textarea><br><input type="submit" name="save" value="Save"> <input type="submit" name="cancel" value="Cancel"> <input type="reset" value="Clear"></form>';
        echo '<div class="ChatBlock"><span>' . $Row['added'] . ' | ' . $Row['date'] . $Admin . '</span>' . $Row['text'] . '</div>';
    }

}

?>