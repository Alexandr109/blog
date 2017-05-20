<?php
ULogin(0);
Head('LogIn') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <form method="POST" action="/account/login">
                <br><input type="text" name="login" placeholder="Login" maxlength="10" pattern="[A-Za-z-0-9]{3,10}"
                           title="More then 3 and lower then 10 latin letters or numbers." required>
                <br><input type="password" name="password" placeholder="Password" maxlength="15"
                           pattern="[A-Za-z-0-9]{5,15}" title="More then 5 and lower then 15 latin letters or numbers"
                           required>
                <div class="capdiv">
                    <input type="text" class="capinp" name="captcha" placeholder="Captcha" maxlength="10"
                                           pattern="[0-9]{1,5}" title="Only numbers." required>
                    <img src="/resource/captcha.php" class="capimg" alt="Captcha">
                </div>
                <br><input type="checkbox" name="remember"> Remember me
                <br><br><input type="submit" name="enter" value="LogIn">
                <input type="reset" value="Clear">
            </form>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>