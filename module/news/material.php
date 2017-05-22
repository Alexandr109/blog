<?php
global $Opt, $EDIT, $Active;

$Param['id'] += 0;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);
$Stmt = $Pdo->query( 'SELECT `name`, `added`, `date`, `text`, `active`, `rate` FROM `news` WHERE `id` = ' . $Param['id'] );
$Row =  $Stmt->fetch(PDO::FETCH_ASSOC);

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
                if (isset($_SESSION['USER_GROUP']) and $_SESSION['USER_GROUP'] == 2)
                    $EDIT = '| <a href="/news/edit/id/' . $Param['id'] . '" class="edit">Edit news</a>
                             | <a href="/news/control/id/' . $Param['id'] . '/command/delete" class="edit">Delete news</a>' . $Active;

                echo ' Added: ' . $Row['added'] . ' | Data: ' . $Row['date'] . ' ' . $EDIT . '<br><br><b>' . $Row['name'] . '</b><br>' . $Row['text'];

                COMMENTS()
            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>