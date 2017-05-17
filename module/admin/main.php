<?php
$pdo = new PDO($dsn, USER, PASS, $opt);

$stmt = $pdo->query( 'SELECT `login`, `regdate`, `group` FROM `users` ORDER BY `regdate` DESC LIMIT 4');
$Result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($Result as $row => $Row) $INFO1 .= '<div class="ChatBlock"><span>Registration data: ' . $Row['regdate'] . '</span>' . UserGroup($Row['group']) . ': ' . $Row['login'] . '</div>';


$stmt = $pdo->query( 'SELECT `id`, `text`, `date` FROM `comments` ORDER BY `date` DESC LIMIT 4');
$Result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($Result as $row => $Row) $INFO2 .= '<div class="ChatBlock"><span>Data: ' . $Row['date'] . ' | <a href="/admin/query/com_delete/' . $Row['id'] . '" class="lol">Удалить</a></span>' . $Row['text'] . '</div>';


Head('Admin panel');
?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php AdminMenu();
        MessageShow()
        ?>
        <div class="Page">
            <div class="Ablock1"><?php echo $INFO1 ?></div>
            <div class="Ablock2"><?php echo $INFO2 ?></div>

            <form method="POST" action="/admin/query">
                <input type="text" name="login" placeholder="User login" required>
                <select size="1" name="group">
                    <option value="0">User</option>
                    <option value="1">Moderator</option>
                    <option value="2">Admin</option>
                </select>
                <input type="submit" name="change_group" value="Change group">
            </form>

        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>