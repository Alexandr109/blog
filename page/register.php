<?php
ULogin(0);
Head('registration') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <form method="POST" action="/account/register">
                <br><input type="text" name="login" placeholder="Login" maxlength="10" pattern="[A-Za-z-0-9]{3,10}"
                           title="More then 3 and lower then 10 latin letters or numbers" required>
                <br><input type="email" name="email" placeholder="E-Mail" required>
                <br><input type="password" name="password" placeholder="Password" maxlength="15"
                           pattern="[A-Za-z-0-9]{5,15}" title="More then 5 and lower then 15 latin letters or numbers"
                           required>
                <br><input type="text" name="name" placeholder="Name" maxlength="10" pattern="[A-Za-z-0-9]{4,10}"
                           title="More then 4 and lower then 10 latin letters or numbers" required>
                <br><select size="1" name="gender">
                    <option value="0">undefined</option>
                    <option value="1">male</option>
                    <option value="2">female</option>

                </select>
                <div class="capdiv"><input type="text" class="capinp" name="captcha" placeholder="Captcha" maxlength="10"
                                           pattern="[0-9]{1,5}" title="Only nubmers" required> <img
                            src="/resource/captcha.php" class="capimg" alt="Captcha"></div>
                <br><input type="submit" name="enter" value="Registration"> <input type="reset" value="Clear">
            </form>
        </div>
    </div>

    <?php Footer(); ?>
</div>
</body>
</html>