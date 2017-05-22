<?php
global $Param, $Opt, $Active;

if ($Module == 'category' and $Param['id'] != 1 and $Param['id'] != 2 and $Param['id'] != 3)
    MessageSend(1, 'Category not found.', '/news');

if (isset($Param['page']))
{
    $Param['page'] += 0;
}

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
            <?php
            if (isset($_SESSION['USER_LOGIN_IN']))
                echo '<a href="/news/add"><div class="Cat">Add news</div></a>' ?>

            <a href="/news">
                <div class="Cat">
                    All categories
                </div>
            </a>

            <a href="/news/category/id/1">
                <div class="Cat">
                    Category 1
                </div>
            </a>

            <a href="/news/category/id/2">
                <div class="Cat">
                    Category 2
                </div>
            </a>

            <a href="/news/category/id/3">
                <div class="Cat">
                    Category 3
                </div>
            </a>
        </div>

        <div class="Page">
            <?php

            $Pdo = new PDO($Dsn, USER, PASS, $Opt);

            if (!$Module or $Module == 'main')
            {
                if (isset($_SESSION['USER_GROUP']) and $_SESSION['USER_GROUP'] != 2) $Active = 'WHERE `active` = 1';
                $Param1 = 'SELECT `id`, `name`, `added`, `date`, `active`, `text` FROM `news` ' . $Active . ' ORDER BY `id` DESC LIMIT 0, 5';
                $Param2 = 'SELECT `id`, `name`, `added`, `date`, `active`, `text` FROM `news` ' . $Active . ' ORDER BY `id` DESC LIMIT START, 5';
                $Param3 = 'SELECT COUNT(`id`) FROM `news`';
                $Param4 = '/news/main/page/';
            }
            else if ($Module == 'category')
            {
                if ($_SESSION['USER_GROUP'] != 2) $Active = 'AND `active` = 1';
                $Param1 = 'SELECT `id`, `name`, `added`, `date`, `active` , `text` FROM `news` 
                           WHERE `cat` = ' . $Param['id'] . ' ' . $Active . ' ORDER BY `id` DESC LIMIT 0, 5';
                $Param2 = 'SELECT `id`, `name`, `added`, `date`, `active` , `text` FROM `news` 
                           WHERE `cat` = ' . $Param['id'] . ' ' . $Active . ' ORDER BY `id` DESC LIMIT START, 5';
                $Param3 = 'SELECT COUNT(`id`) FROM `news` 
                           WHERE `cat` = ' . $Param['id'];
                $Param4 = '/news/category/id/' . $Param['id'] . '/page/';
            }

            $Stmt = $Pdo->query($Param3);
            $Count = $Stmt->fetch(PDO::FETCH_NUM);

            if (!isset($Param['page']))
            {
                $Param['page'] = 1;
                $Stmt = $Pdo->query( $Param1 );
                $Result =  $Stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else
            {
                $Start = ($Param['page'] - 1) * 5;
                $Stmt = $Pdo->query( str_replace('START', $Start, $Param2));
                $Result =  $Stmt->fetchAll(PDO::FETCH_ASSOC);
            }


            PageSelector($Param4, $Param['page'], $Count);

            foreach ($Result as $row => $Row)
            {
                $String = substr($Row['text'], 0, 200) . '....';
                echo
                    '<a href="/news/material/id/' . $Row['id'] . '">
                        <div class="ChatBlock">
                            <span>Added: ' . $Row['added'] . ' | ' . $Row['date'] . '</span>' .
                            $Row['name'] . '<br><br>' .
                            $String . '
                            <br><br>Read more.
                        </div>
                    </a>';
            }
            ?>
        </div>
    </div><?php Footer() ?>

</div>
</body>
</html>

