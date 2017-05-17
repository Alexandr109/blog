<?php
if ($Module == 'category' and $Param['id'] != 1 and $Param['id'] != 2 and $Param['id'] != 3) MessageSend(1, 'Такой категории не существует.', '/news');
$Param['page'] += 0;
Head('News');
?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="CatHead">
            <?php if ($_SESSION['USER_LOGIN_IN']) echo '<a href="/news/add"><div class="Cat">Add news</div></a>' ?>
            <a href="/news">
                <div class="Cat">All categories</div>
            </a>
            <a href="/news/category/id/1">
                <div class="Cat">Category 1</div>
            </a>
            <a href="/news/category/id/2">
                <div class="Cat">Category 2</div>
            </a>
            <a href="/news/category/id/3">
                <div class="Cat">Category 3</div>
            </a>
        </div>

        <div class="Page">
            <?php
            $pdo = new PDO($dsn, USER, PASS, $opt);
            if (!$Module or $Module == 'main') {
                if ($_SESSION['USER_GROUP'] != 2) $Active = 'WHERE `active` = 1';
                $Param1 = 'SELECT `id`, `name`, `added`, `date`, `active`, `text` FROM `news` ' . $Active . ' ORDER BY `id` DESC LIMIT 0, 5';
                $Param2 = 'SELECT `id`, `name`, `added`, `date`, `active`, `text` FROM `news` ' . $Active . ' ORDER BY `id` DESC LIMIT START, 5';
                $Param3 = 'SELECT COUNT(`id`) FROM `news`';
                $Param4 = '/news/main/page/';
            } else if ($Module == 'category') {
                if ($_SESSION['USER_GROUP'] != 2) $Active = 'AND `active` = 1';
                $Param1 = 'SELECT `id`, `name`, `added`, `date`, `active` , `text` FROM `news` WHERE `cat` = ' . $Param['id'] . ' ' . $Active . ' ORDER BY `id` DESC LIMIT 0, 5';
                $Param2 = 'SELECT `id`, `name`, `added`, `date`, `active` , `text` FROM `news` WHERE `cat` = ' . $Param['id'] . ' ' . $Active . ' ORDER BY `id` DESC LIMIT START, 5';
                $Param3 = 'SELECT COUNT(`id`) FROM `news` WHERE `cat` = ' . $Param['id'];
                $Param4 = '/news/category/id/' . $Param['id'] . '/page/';
            }

            $stmt = $pdo->query($Param3);
            $Count = $stmt->fetch(PDO::FETCH_NUM);

            if (!$Param['page']) {
                $Param['page'] = 1;
                $stmt = $pdo->query( $Param1 );
                $Result =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            } else {
                $Start = ($Param['page'] - 1) * 5;
                $stmt = $pdo->query( str_replace('START', $Start, $Param2));
                $Result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            }


            PageSelector($Param4, $Param['page'], $Count);

            foreach ($Result as $row => $Row)
            {
                $string = substr($Row['text'], 0, 200) . '....';

                echo '<a href="/news/material/id/' . $Row['id'] . '">
                <div class="ChatBlock">
                <span>Added: ' . $Row['added'] . ' | ' . $Row['date'] . '</span>' . $Row['name'] . '<br><br>' . $string . '<br><br>Read more.
                </div>
                </a>';
            }
            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>

