<?php
$Param['id'] += 0;
$pdo = new PDO($dsn, USER, PASS, $opt);
$stmt = $pdo->query( 'SELECT `name`, `added`, `date`, `text`, `active`, `rate` FROM `news` WHERE `id` = ' . $Param['id'] );
$Row =  $stmt->fetch(PDO::FETCH_ASSOC);

Head($Row['name']);
?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <?php
            if ($_SESSION['USER_GROUP'] == 2) $EDIT = '| <a href="/news/edit/id/' . $Param['id'] . '" class="lol">Edit news</a> | <a href="/news/control/id/' . $Param['id'] . '/command/delete" class="lol">Delete news</a>' . $Active;
            echo ' Added: ' . $Row['added'] . ' | Data: ' . $Row['date'] . ' ' . $EDIT . '<br><br><b>' . $Row['name'] . '</b><br>' . $Row['text'];
            COMMENTS()
            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>