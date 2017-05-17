<?php
$pdo = new PDO($dsn, USER, PASS, $opt);
if ($Module == 'logout' and $_SESSION['USER_LOGIN_IN'] == 1) {
    if ($_COOKIE['user']) {
        setcookie('user', '', strtotime('-30 days'), '/');
        unset($_COOKIE['user']);
    }
    session_unset();
    exit(header('Location: /login'));
}


if ($Module == 'edit' and $_POST['enter']) {
    ULogin(1);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['gender'] = FormChars($_POST['gender']);




    if ($_POST['name'] != $_SESSION['USER_NAME']) {
        $query = "UPDATE `users`  SET `name` = '$_POST[name]' WHERE `id` = $_SESSION[USER_ID]";
        $name = $_POST['name'];
        $id = $_SESSION['USER_ID'];
        $upd = $pdo->prepare($query);
        $upd ->bindValue(":name",$name);
        $upd ->bindValue(":id",$id);
        $upd ->execute();
        $_SESSION['USER_NAME'] = $_POST['name'];
    }


    if (UserGender($_POST['gender']) != $_SESSION['USER_GENDER']) {
        $query = "UPDATE `users`  SET `gender` = :gender] WHERE `id` = :ID";
        $gender = $_POST['gender'];
        $ID = $_SESSION[USER_ID];
        $upd = $pdo->prepare($query);
        $upd ->bindValue(":gender",$gender);
        $upd ->bindValue(":ID",$ID);
        $upd ->execute();


        $_SESSION['USER_GENDER'] = UserGender($_POST['gender']);
    }


    if ($_FILES['avatar']['tmp_name']) {
        if ($_FILES['avatar']['type'] != 'image/jpeg') MessageSend(2, 'Image onlu jpeg.');
        if ($_FILES['avatar']['size'] > 20000) MessageSend(2, 'Big image.');
        $Image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
        $Size = getimagesize($_FILES['avatar']['tmp_name']);
        $Tmp = imagecreatetruecolor(120, 120);
        imagecopyresampled($Tmp, $Image, 0, 0, 0, 0, 120, 120, $Size[0], $Size[1]);
        if ($_SESSION['USER_AVATAR'] == 0) {
            $Files = glob('resource/avatar/*', GLOB_ONLYDIR);
            foreach($Files as $num => $Dir) {
                $Num ++;
                $Count = sizeof(glob($Dir.'/*.*'));
                if ($Count < 250) {
                    $Download = $Dir.'/'.$_SESSION['USER_ID'];
                    $_SESSION['USER_AVATAR'] = $Num;

                    $query = "UPDATE `users`  SET `avatar` = :avatar WHERE `id` = :ID";
                    $avatar =$Num;
                    $ID = $_SESSION[USER_ID];
                    $upd = $pdo->prepare($query);
                    $upd ->bindValue(":avatar",$avatar);
                    $upd ->bindValue(":ID",$ID);
                    $upd ->execute();





                    break;
                }
            }
        }
        else $Download = 'resource/avatar/'.$_SESSION['USER_AVATAR'].'/'.$_SESSION['USER_ID'];
        imagejpeg($Tmp, $Download.'.jpg');
        imagedestroy($Image);
        imagedestroy($Tmp);
    }




    MessageSend(3, 'Data chenged.');
}









ULogin(0);












if ($Module == 'register' and $_POST['enter']) {
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['gender'] = FormChars($_POST['gender']);
    $_POST['captcha'] = FormChars($_POST['captcha']);
    if (!$_POST['login'] or !$_POST['email'] or !$_POST['password'] or !$_POST['name'] or $_POST['gender'] > 4 or !$_POST['captcha']) MessageSend(1, 'Error.');
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) MessageSend(1, 'Invalid Captcha.');

    {
        $query = "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'";
        $stmt = $pdo->query($query);
        $Row =  $stmt->fetch(PDO::FETCH_ASSOC);
        //$Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'"));
    }

    if ($Row['login']) exit('login <b>'.$_POST['login'].'</b> is alredy used.');
    {
        $query = "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'";
        $stmt = $pdo->query($query);
        $Row =  $stmt->fetch(PDO::FETCH_ASSOC);
        //$Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'"));
    }
    if ($Row['email']) exit('E-Mail <b>'.$_POST['email'].'</b> is alredy used.');
    {

        $ID ='';
        $login= $_POST['login'];
        $password= $_POST['password'];
        $name = $_POST['name'];
        $regdate = date("Y-m-d H:i:s");
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $avatar = 0;
        $active = 1;
        $group = 0;


        $pdoQuery = "INSERT INTO users (`id`,`login`,`password`,`name`,`regdate`, `email`,`gender`, `avatar`, `active`, `group`) 
                    VALUES (:id,:login,:password,:name,:regdate, :email,:gender, :avatar, :active, :group)";
        $pdoResult = $pdo->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute(array(":id"=>$ID,":login"=>$login,":password"=>$password,":name"=>$name,":regdate" =>$regdate, ":email"=>$email,":gender"=>$gender, ":avatar"=>$avatar, ":active"=>$avatar, ":group"=>$group));

        //mysqli_query($CONNECT, "INSERT INTO `users`  VALUES ('', '$_POST[login]', '$_POST[password]', '$_POST[name]', NOW(), '$_POST[email]', $_POST[gender], 0, 1, 0)");
    }
    $Code = str_replace('=', '', base64_encode($_POST['email']));
    MessageSend(3, 'Registration comlited. ');
}







else if ($Module == 'login' and $_POST['enter']) {
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['captcha'] = FormChars($_POST['captcha']);
    if (!$_POST['login'] or !$_POST['password'] or !$_POST['captcha']) MessageSend(1, 'Error.');
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) MessageSend(1, 'Invalid Captcha.');
    {
        $query ="SELECT `password`, `active` FROM `users` WHERE `login` = '$_POST[login]'";
        $stmt = $pdo->query($query);
        $Row =  $stmt->fetch(PDO::FETCH_ASSOC);
        //$Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `password`, `active` FROM `users` WHERE `login` = '$_POST[login]'"));
    }
    if ($Row['password'] != $_POST['password']) MessageSend(1, 'Invalid login.');
    {
        $query = "SELECT `id`, `name`, `regdate`, `email`, `gender`, `avatar`, `password`, `login`, `group` FROM `users` WHERE `login` = '$_POST[login]'";
        $stmt = $pdo->query($query);
        $Row =  $stmt->fetch(PDO::FETCH_ASSOC);
        //$Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `id`, `name`, `regdate`, `email`, `gender`, `avatar`, `password`, `login`, `group` FROM `users` WHERE `login` = '$_POST[login]'"));
    }
    $_SESSION['USER_LOGIN'] = $Row['login'];
    $_SESSION['USER_PASSWORD'] = $Row['password'];
    $_SESSION['USER_ID'] = $Row['id'];
    $_SESSION['USER_NAME'] = $Row['name'];
    $_SESSION['USER_REGDATE'] = $Row['regdate'];
    $_SESSION['USER_EMAIL'] = $Row['email'];
    $_SESSION['USER_GENDER'] = UserGender($Row['gender']);
    $_SESSION['USER_AVATAR'] = $Row['avatar'];
    $_SESSION['USER_GROUP'] = $Row['group'];
    $_SESSION['USER_LOGIN_IN'] = 1;
    if ($_REQUEST['remember']) setcookie('user', $_POST['password'], strtotime('+30 days'), '/');
    exit(header('Location: /profile'));
}
?>