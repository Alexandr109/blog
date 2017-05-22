<?php
global $Opt;

global $Message, $Num;
$Pdo = new PDO($Dsn, USER, PASS, $Opt);

if (isset($_SESSION['USER_LOGIN_IN'])and $Module == 'logout' and $_SESSION['USER_LOGIN_IN'] == 1) {
    if (isset($_COOKIE['user'])) {
        setcookie('user', '', strtotime('-30 days'), '/', null, null, true);
        unset($_COOKIE['user']);
    }
    session_unset();
    exit(header('Location: /login'));
}


if ($Module == 'edit' and $_POST['enter'])
{
        ULogin(1);
        //xss pretect
        $_POST['name'] = FormChars($_POST['name']);

        if ($_POST['name'] != $_SESSION['USER_NAME'])
        {
            $Query = "UPDATE `users`  SET `name` = '$_POST[name]' WHERE `id` = $_SESSION[USER_ID]";
            $Name = $_POST['name'];
            $Id = $_SESSION['USER_ID'];
            $Upd = $Pdo->prepare($Query);
            $Upd ->bindValue(":name",$Name);
            $Upd ->bindValue(":id",$Id);
            $Upd ->execute();
            $_SESSION['USER_NAME'] = $_POST['name'];
        }


        if ($_FILES['avatar']['tmp_name'])
        {
            if ($_FILES['avatar']['type'] != 'image/jpeg') MessageSend(2, 'Image onlu jpeg.');
            if ($_FILES['avatar']['size'] > 20000) MessageSend(2, 'Big image.');
            $Image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
            $Size = getimagesize($_FILES['avatar']['tmp_name']);
            $Tmp = imagecreatetruecolor(120, 120);
            imagecopyresampled($Tmp, $Image, 0, 0, 0, 0, 120, 120, $Size[0], $Size[1]);
            if ($_SESSION['USER_AVATAR'] == 0)
            {
                $Files = glob('resource/avatar/*', GLOB_ONLYDIR);
                foreach($Files as $num => $Dir)
                {
                    $Num ++;
                    $Count = sizeof(glob($Dir.'/*.*'));
                    if ($Count < 250)
                    {
                        $Download = $Dir.'/'.$_SESSION['USER_ID'];
                        $_SESSION['USER_AVATAR'] = $Num;

                        $Query = "UPDATE `users`  SET `avatar` = :avatar WHERE `id` = :ID";
                        $Avatar =$Num;
                        $ID = $_SESSION['USER_ID'];
                        $Upd = $Pdo->prepare($Query);
                        $Upd ->bindValue(":avatar",$Avatar);
                        $Upd ->bindValue(":ID",$ID);
                        $Upd ->execute();
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

if ($Module == 'register' and $_POST['enter'])
{
    //xss protect
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['gender'] = FormChars($_POST['gender']);
    $_POST['captcha'] = FormChars($_POST['captcha']);

    if (!$_POST['login'] or !$_POST['email'] or !$_POST['password'] or !$_POST['name'] or $_POST['gender'] > 4 or !$_POST['captcha'])
        MessageSend(1, 'Error.');

    if ($_SESSION['captcha'] != md5($_POST['captcha'])) MessageSend(1, 'Invalid Captcha.');
    {
        $Query = "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'";
        $Stmt = $Pdo->query($Query);
        $Row =  $Stmt->fetch(PDO::FETCH_ASSOC);
    }


    if ($Row['login']) exit('login <b>'.$_POST['login'].'</b> is alredy used.');
    {
        $Query = "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'";
        $Stmt = $Pdo->query($Query);
        $Row =  $Stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($Row['email']) exit('E-Mail <b>'.$_POST['email'].'</b> is alredy used.');
    {
        $ID ='';
        $Login= $_POST['login'];
        $Password= $_POST['password'];
        $Name = $_POST['name'];
        $Regdate = date("Y-m-d H:i:s");
        $Email = $_POST['email'];
        $Gender = $_POST['gender'];
        $Avatar = 0;
        $Active = 1;
        $Group = 0;

        $pdoQuery = "INSERT INTO users (`id`,`login`,`password`,`name`,`regdate`, `email`,`gender`, `avatar`, `active`, `group`) 
                     VALUES (:id,:login,:password,:name,:regdate, :email,:gender, :avatar, :active, :group)";
        $pdoResult = $Pdo->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute(array(":id"=>$ID,":login"=>$Login,":password"=>$Password,":name"=>$Name,":regdate" =>$Regdate, ":email"=>$Email,":gender"=>$Gender, ":avatar"=>$Avatar, ":active"=>$Avatar, ":group"=>$Group));
    }

    $Code = str_replace('=', '', base64_encode($_POST['email']));
    MessageSend(3, 'Registration comlited. ');
}







else if ($Module == 'login' and $_POST['enter'])
{
    //xss protect
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);
    $_POST['captcha'] = FormChars($_POST['captcha']);

    if (!$_POST['login'] or !$_POST['password'] or !$_POST['captcha']) MessageSend(1, 'Error.');
    if ($_SESSION['captcha'] != md5($_POST['captcha'])) MessageSend(1, 'Invalid Captcha.');
    {
        $Query ="SELECT `password`, `active` FROM `users` WHERE `login` = '$_POST[login]'";
        $Stmt = $Pdo->query($Query);
        $Row =  $Stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($Row['password'] != $_POST['password']) MessageSend(1, 'Invalid login.');
    {
        $Query = "SELECT `id`, `name`, `regdate`, `email`, `gender`, `avatar`, `password`, `login`, `group` FROM `users` WHERE `login` = '$_POST[login]'";
        $Stmt = $Pdo->query($Query);
        $Row =  $Stmt->fetch(PDO::FETCH_ASSOC);
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

    if (isset($_REQUEST['remember']) and  $_REQUEST['remember']) setcookie('user', $_POST['password'], strtotime('+30 days'), '/');
    exit(header('Location: /profile'));
}
?>