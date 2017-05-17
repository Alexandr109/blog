<?php

$pdo = new PDO($dsn, USER, PASS, $opt);

UAccess(2);
$Param['id'] += 0;
if (!$Param['id']) MessageSend(1, 'Invalid ID', '/news');
{
     $stmt = $pdo->query( 'SELECT `name`, `added`, `date`, `text`, `active`, `rate`, `rateusers` FROM `news` WHERE `id` = ' . $Param['id'] );
    $Row =  $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$Row['name']) MessageSend(1, 'News not found', '/news');

if ($_POST['enter'] and $_POST['text'] and $_POST['name'] and $_POST['cat']) {
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['text'] = FormChars($_POST['text']);
    $_POST['cat'] += 0;
    {
        $query  = "UPDATE `news` SET `name` = '$_POST[name]', `cat` = $_POST[cat], `text` = '$_POST[text]' WHERE `id` = $Param[id]";

        $name = $_POST['name'];
        $cat = $_POST['cat'];
        $text = $_POST['text'];
        $id = $Param['id'];

        $upd = $pdo->prepare($query);
        $upd ->bindValue(":name",$name);
        $upd ->bindValue(":cat",$cat);
        $upd ->bindValue(":text",$text);
        $upd ->bindValue(":id",$id);
        $upd ->execute();

    }
    MessageSend(2, 'News edited.', '/news/material/id/' . $Param['id']);
}

Head('Edit news') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <?php
            echo '<form method="POST" action="/news/edit/id/' . $Param['id'] . '">
            <input type="text" name="name" placeholder="News title" value="' . $Row['name'] . '" required>
            <br><select size="1" name="cat">' . str_replace('value="' . $Row['cat'], 'selected value="' . $Row['cat'], '<option value="1">Category 1</option><option value="2">Category 2</option><option value="3">Category 3</option>') . '</select>
            <br><textarea class="Add" name="text" required>' . str_replace('<br>', '', $Row['text']) . '</textarea>
            <br><input type="submit" name="enter" value="Save"> <input type="reset" value="Clear">
            </form>'
            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>