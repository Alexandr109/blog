<?php Head('My Blog') ?>
<body>
<div class="wrapper">
    <div class="header"></div>
    <div class="content">
        <?php Menu();
        MessageShow()
        ?>
        <div class="Page">
            <?php
            //Main site
            echo 'Please go in news';
            ?>
        </div>
    </div>

    <?php Footer() ?>
</div>
</body>
</html>