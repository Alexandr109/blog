<?php
$pdo = new PDO($dsn, USER, PASS, $opt);

if ($_SESSION['USER_GROUP'] != 2) MessageSend(2, 'Add news can only admin.', '/news');

if ($_SESSION['USER_GROUP'] == 2) $Active = 1;
else $Active = 0;

if ($_POST['enter'] and $_POST['text'] and $_POST['name'] and $_POST['cat']) {
    //xss pretect
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['text'] = FormChars($_POST['text']);
    $_POST['cat'] += 0;

    $id='';
    $name=$_POST['name'];
    $cat = $_POST['cat'];
    $added =$_SESSION['USER_LOGIN'];
    $text = $_POST['text'];
    $date = date("Y-m-d H:i:s");
    $active = 1;
    $rate = '';



    $pdoQuery = "INSERT INTO news (`id`,`name`,`cat`,`added`,`text`, `date`,`active`,`rate`) VALUES (:id,:name,:cat,:added,:text, :date,:active,:rate)";
    $pdoResult = $pdo->prepare($pdoQuery);
    $pdoExec = $pdoResult->execute(array(":id"=>$id,":name"=>$name,":cat"=>$cat,":added"=>$added,":text"=>$text, ":date"=>$date,"active"=>$active,"rate"=>$rate));

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