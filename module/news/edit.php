<?php
global $Opt;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);

UAccess(2);
$Param['id'] += 0;
if (!$Param['id']) MessageSend(1, 'Invalid ID', '/news');
{
    $Stmt = $Pdo->query( 'SELECT `name`, `added`, `date`, `text`, `active`, `rate`, `cat` FROM `news` WHERE `id` = ' . $Param['id'] );
    $Row =  $Stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$Row['name']) MessageSend(1, 'News not found', '/news');

if (isset($_POST['enter']) and isset($_POST['text']) and isset($_POST['name']) and isset($_POST['cat']))
{
    //xss protect
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['text'] = FormChars($_POST['text']);
    $_POST['cat'] += 0;
    {
        $Query  = "UPDATE `news` SET `name` = '$_POST[name]', `cat` = $_POST[cat], `text` = '$_POST[text]' WHERE `id` = $Param[id]";

        $Name = $_POST['name'];
        $cat = $_POST['cat'];
        $Text = $_POST['text'];
        $Id = $Param['id'];

        $Upd = $Pdo->prepare($Query);
        $Upd ->bindValue(":name",$Name);
        $Upd ->bindValue(":cat",$cat);
        $Upd ->bindValue(":text",$Text);
        $Upd ->bindValue(":id",$Id);
        $Upd ->execute();

    }
    MessageSend(2, 'News edited.', '/news/material/id/' . $Param['id']);
}

Head('Edit news') ?>


<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php
        Menu();
        MessageShow()
        ?>
        <div class="Page">
            <?php
            $Cat = $Row['cat'];
            $Text = $Row['text'];

            echo '            
                <form method="POST" action="/news/edit/id/' . $Param['id'] . '">
                    <input type="text" name="name" placeholder="News title" value="' . $Row['name'] . '" required><br>
                        <select size="1" name="cat">' .
                            str_replace('value="' . $Row['cat'], 'selected value="' . $Row['cat'], '
                            <option value="1">Category 1</option>
                            <option value="2">Category 2</option>
                            <option value="3">Category 3</option>') .
                        '</select><br>                       
                    <textarea class="Add" name="text" required>' . str_replace('<br>', '', $Row['text']) . '</textarea><br>
                    <input type="submit" name="enter" value="Save"> <input type="reset" value="Clear">
                </form>';

            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>