<?php
global $Opt;

$Pdo = new PDO($Dsn, USER, PASS, $Opt);

if ($_SESSION['USER_GROUP'] != 2) MessageSend(2, 'Add news can only admin.', '/news');

if ($_SESSION['USER_GROUP'] == 2) $Active = 1;
else $Active = 0;

if (isset($_POST['enter']) and isset($_POST['text']) and isset($_POST['name']) and isset($_POST['cat'])) {
    //xss pretect
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['text'] = FormChars($_POST['text']);
    $_POST['cat'] += 0;

    $Id='';
    $Name=$_POST['name'];
    $Cat = $_POST['cat'];
    $Added =$_SESSION['USER_LOGIN'];
    $Text = $_POST['text'];
    $Date = date("Y-m-d H:i:s");
    $active = 1;
    $Rate = '';



    $PdoQuery = "INSERT INTO news (`id`,`name`,`cat`,`added`,`text`, `date`,`active`,`rate`) VALUES (:id,:name,:cat,:added,:text, :date,:active,:rate)";
    $PdoResult = $Pdo->prepare($PdoQuery);
    $PdoExec = $PdoResult->execute(array(":id"=>$Id,":name"=>$Name,":cat"=>$Cat,":added"=>$Added,":text"=>$Text, ":date"=>$Date,"active"=>$active,"rate"=>$Rate));

    MessageSend(2, 'News added', '/news');
}
Head('Add news') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <form method="POST" action="/news/add">
                <input type="text" name="name" placeholder="News title" required>
                <br><select size="1" name="cat">
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                    <option value="3">Category 3</option>
                </select>
                <br><textarea class="Add" name="text" required></textarea>
                <br><input type="submit" name="enter" value="Add"> <input type="reset" value="Clear">
            </form>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>