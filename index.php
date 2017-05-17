<?php
include_once 'setting.php';
session_start();


if ($_SESSION['USER_LOGIN_IN'] != 1 and $_COOKIE['user']) {



    $opt = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $pdo = new PDO($dsn, USER, PASS, $opt);

    $stmt = $pdo->query("SELECT `id`, `name`, `regdate`, `email`, `GENDER`, `avatar`, `login`, `group` FROM `users` WHERE `password` = '$_COOKIE[user]'");


    foreach ($stmt as $Row) {

        $_SESSION['USER_ID'] = $Row['id'];
        $_SESSION['USER_LOGIN'] = $Row['login'];
        $_SESSION['USER_NAME'] = $Row['name'];
        $_SESSION['USER_REGDATE'] = $Row['regdate'];
        $_SESSION['USER_EMAIL'] = $Row['email'];
        $_SESSION['USER_GENDER'] = UserGender($Row['GENDER']);
        $_SESSION['USER_AVATAR'] = $Row['avatar'];
        $_SESSION['USER_GROUP'] = $Row['group'];
        $_SESSION['USER_LOGIN_IN'] = 1;
    }

}


if ($_SERVER['REQUEST_URI'] == '/') {
    $Page = 'index';
    $Module = 'index';
} else {
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $URL_Parts = explode('/', trim($URL_Path, ' /'));
    $Page = array_shift($URL_Parts);
    $Module = array_shift($URL_Parts);


    if (!empty($Module)) {
        $Param = array();
        for ($i = 0; $i < count($URL_Parts); $i++) {
            $Param[$URL_Parts[$i]] = $URL_Parts[++$i];
        }
    }
}


if ($Page == 'index') include('page/index.php');
else if ($Page == 'login') include('page/login.php');
else if ($Page == 'register') include('page/register.php');
else if ($Page == 'account') include('form/account.php');
else if ($Page == 'profile') include('page/profile.php');
else if ($Page == 'restore') include('page/restore.php');
else if ($Page == 'chat') include('page/chat.php');

else if ($Page == 'news') {
    if (!$Module or $Page == 'news' and $Module == 'category' or $Page == 'news' and $Module == 'main') include('module/news/main.php');
    else if ($Module == 'material') {
        include('module/comments/main.php');
        include('module/news/material.php');
    } else if ($Module == 'add') include('module/news/add.php');
    else if ($Module == 'edit') include('module/news/edit.php');
    else if ($Module == 'control') include('module/news/control.php');
} else if ($Page == 'comments') {
    if ($Module == 'add') include('module/comments/add.php');
    else if ($Module == 'control') include('module/comments/control.php');
} else if ($Page == 'admin') {
    if ($_SESSION['ADMIN_LOGIN_IN']) {
        if (!$Module) include('module/admin/main.php');
        else if ($Module == 'stats') include('module/admin/stats.php');
        else if ($Module == 'query') include('module/admin/query.php');
    } else {
        if ($Module == ADMIN_PASS) {
            $_SESSION['ADMIN_LOGIN_IN'] = 1;
            MessageSend(3, 'Entering to admin panel complited.', '/admin');
        }
    }
}


function ULogin($p1)
{
    if ($p1 <= 0 and $_SESSION['USER_LOGIN_IN'] != $p1) MessageSend(1, 'This page only for guests.', '/');
    else if ($_SESSION['USER_LOGIN_IN'] != $p1) MessageSend(1, 'This page only for users.', '/');
}


function MessageSend($p1, $p2, $p3 = '')
{
    if ($p1 == 1) $p1 = 'Error';
    else if ($p1 == 2) $p1 = 'Help';
    else if ($p1 == 3) $p1 = 'Information';
    $_SESSION['message'] = '<div class="MessageBlock"><b>' . $p1 . '</b>: ' . $p2 . '</div>';
    if ($p3) $_SERVER['HTTP_REFERER'] = $p3;
    exit(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


function MessageShow()
{
    if ($_SESSION['message']) $Message = $_SESSION['message'];
    echo $Message;
    $_SESSION['message'] = array();
}


function UserGender($p1)
{
    if ($p1 == 0) return 'undefined';
    else if ($p1 == 1) return 'male';
    else if ($p1 == 2) return 'female';
}


function UserGroup($p1)
{
    if ($p1 == 0) return 'User';
    else if ($p1 == 1) return 'Moderator';
    else if ($p1 == 2) return 'Admin';
    else if ($p1 == -1) return 'Banned';
}


function UAccess($p1)
{
    if ($_SESSION['USER_GROUP'] < $p1) MessageSend(1, 'U dont have rule.', '/');
}


function RandomString($p1)
{
    $String = "";
    $Char = '0123456789abcdefghijklmnopqrstuvwxyz';
    for ($i = 0; $i < $p1; $i++)
        $String .= $Char[rand(0, strlen($Char) - 1)];
    return $String;
}

function HideEmail($p1)
{
    $Explode = explode('@', $p1);
    return $Explode[0] . '@*****';
}


function FormChars($p1)
{
    return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}


function GenPass($p1, $p2)
{
    return md5('GENPASS' . md5('321' . $p1 . '123') . md5('678' . $p2 . '890'));
}

function Head($p1)
{
    echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>' . $p1 . '</title><meta name="keywords" content="" /><meta name="description" content="" /><link href="/resource/style.css" rel="stylesheet"><link rel="icon" href="/resource/img/favicon.ico" type="image/x-icon"></head>';
}

function ModuleID($p1)
{
    if ($p1 == 'news') return 1;
    else if ($p1 == 'loads') return 2;
    else MessageSend(1, 'Module not found.', '/');
}


//number page

function PageSelector($p1, $p2, $p3, $p4 = 5)
{
    $Page = ceil($p3[0] / $p4);
    if ($Page > 1) {
        echo '<div class="PageSelector">';
        for ($i = ($p2 - 3); $i < ($Page + 1); $i++) {
            if ($i > 0 and $i <= ($p2 + 3)) {
                if ($p2 == $i) $Swch = 'SwchItemCur';
                else $Swch = 'SwchItem';
                echo '<a class="' . $Swch . '" href="' . $p1 . $i . '">' . $i . '</a>';
            }
        }
        echo '</div>';
    }
}


function MiniIMG($p1, $p2, $p3, $p4, $p5 = 50)
{
    /*
    $p1 - path to picture.
    $p2 - path to little picture.
    $p3 - long.
    $p4 - hight.
    $p5 - quality.
    */
    $Scr = imagecreatefromjpeg($p1);
    $Size = getimagesize($p1);
    $Tmp = imagecreatetruecolor($p3, $p4);
    imagecopyresampled($Tmp, $Scr, 0, 0, 0, 0, $p3, $p4, $Size[0], $Size[1]);
    imagejpeg($Tmp, $p2, $p5);
    imagedestroy($Scr);
    imagedestroy($Tmp);
}


function AdminMenu()
{
    echo '<div class="MenuHead"><a href="/admin"><div class="Menu">Main</div></a><a href="/admin/query/logout/1"><div class="Menu">Exit</div></a></div>';
}


function Menu()
{
    if ($_SESSION['USER_LOGIN_IN'] != 1)
        $Menu =
            '<a href="/register"><div class="Menu">Registration</div></a>
            <a href="/login"><div class="Menu">LogIn</div></a>';
    else $Menu = '<a href="/profile"><div class="Menu">Profile</div></a> ';

    echo '<div class="MenuHead"><a href="/news"><div class="Menu">News</div></a>' . $Menu . '</div>';
}

function Footer()
{

}

?>