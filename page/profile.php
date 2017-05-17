<?php
ULogin(1);
Head('User profile') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">

            <?php
            if ($_SESSION['USER_AVATAR'] == 0) $Avatar = 0;
            else $Avatar = $_SESSION['USER_AVATAR'] . '/' . $_SESSION['USER_ID'];

            echo '
<img src="/resource/avatar/' . $Avatar . '.jpg" width="120" height="120" alt="Аватар" align="left">
<div class="Block">
ID ' . $_SESSION['USER_ID'] . ' (' . UserGroup($_SESSION['USER_GROUP']) . ')
<br>Name ' . $_SESSION['USER_NAME'] . '
<br>E-mail ' . $_SESSION['USER_EMAIL'] . '
<br>Gender ' . $_SESSION['USER_GENDER'] . '
<br>Registration data ' . $_SESSION['USER_REGDATE'] . '
</div>
<a href="/account/logout" class="button ProfileB">LogOut</a><br><br>
<div class="ProfileEdit">
<form method="POST" action="/account/edit" enctype="multipart/form-data">
<br><input type="text" name="name" placeholder="Name" maxlength="10" pattern="[A-Za-z-0-9]{4,10}" title="More then 4 and lower then 10 latin letters or numbers" value="' . $_SESSION['USER_NAME'] . '" required>
<br><input type="file" name="avatar">
<br><br><input type="submit" name="enter" value="Save"> <input type="reset" value="Clear">
</form>
</div>
';
            ?>


        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>