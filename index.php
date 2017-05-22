<?php

global $_SESSION;

include_once 'setting.php';
if (session_status !== PHP_SESSION_ACTIVE) {
    session_start();
}

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

if ((isset($_SESSION['USER_LOGIN_IN'])) and $_SESSION['USER_LOGIN_IN'] != 1 and isset($_COOKIE['user'])) {



    $Opt = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    $Pdo = new PDO($Dsn, USER, PASS, $Opt);

    $Stmt = $Pdo->query("SELECT `id`, `name`, `regdate`, `email`, `GENDER`, `avatar`, `login`, `group` FROM `users` WHERE `password` = '$_COOKIE[user]'");


    foreach ($Stmt as $Row) {

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


if ($_SERVER['REQUEST_URI'] == '/')
{
    $Page = 'index';
    $Module = 'index';
}
else
{
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $URL_Parts = explode('/', trim($URL_Path, ' /'));
    $Page = array_shift($URL_Parts);
    $Module = array_shift($URL_Parts);


    if (!empty($Module))
    {
        $Param = array();
        for ($i = 0; $i < count($URL_Parts); $i++)
        {
            $Param[$URL_Parts[$i]] = $URL_Parts[++$i];
        }
    }
}


if ($Page == 'index') include('page/index.php');
    else if ($Page == 'login') include('page/login.php');
    else if ($Page == 'register') include('page/register.php');
    else if ($Page == 'account') include('form/account.php');
    else if ($Page == 'profile') include('page/profile.php');


else if ($Page == 'news')
{
    if (!$Module or $Page == 'news' and $Module == 'category' or $Page == 'news' and $Module == 'main')
        include('module/news/main.php');
    else if ($Module == 'material')
    {
        include('module/comments/main.php');
        include('module/news/material.php');
    }
    else if ($Module == 'add') include('module/news/add.php');
    else if ($Module == 'edit') include('module/news/edit.php');
    else if ($Module == 'control') include('module/news/control.php');
}
else if ($Page == 'comments')
{
    if ($Module == 'add') include('module/comments/add.php');
    else if ($Module == 'control') include('module/comments/control.php');
}
else if ($Page == 'admin')
{
    if (isset($_SESSION['ADMIN_LOGIN_IN']))
    {
        if (!$Module) include('module/admin/main.php');
        else if ($Module == 'stats') include('module/admin/stats.php');
        else if ($Module == 'query') include('module/admin/query.php');
    }
    else
        {
        if ($Module == ADMIN_PASS) {
            $_SESSION['ADMIN_LOGIN_IN'] = 1;
            MessageSend(3, 'Entering to admin panel complited.', '/admin');
        }
    }
}


function ULogin($p1)
{
    if (isset($_SESSION['USER_LOGIN_IN'])and $p1 <= 0 and $_SESSION['USER_LOGIN_IN'] != $p1)
        MessageSend(1, 'This page only for guests.', '/');
    else if (isset($_SESSION['USER_LOGIN_IN']) and $_SESSION['USER_LOGIN_IN'] != $p1)
        MessageSend(1, 'This page only for users.', '/');
}


function MessageSend($P1, $P2, $P3 = '')
{
    if ($P1 == 1) $P1 = 'Error';
    else if ($P1 == 2) $P1 = 'Help';
    else if ($P1 == 3) $P1 = 'Information';
    $_SESSION['message'] = '<div class="MessageBlock"><b>' . $P1 . '</b>: ' . $P2 . '</div>';
    if ($P3) $_SERVER['HTTP_REFERER'] = $P3;
    exit(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


function MessageShow()
{   if(isset($_SESSION['message']))
{
    if (($_SESSION['message'])) {

        $Message = $_SESSION['message'];

        echo $Message;

        $_SESSION['message'] = array();
    }
}}


function UserGender($P1)
{
    if ($P1 == 0) return 'undefined';
    else if ($P1 == 1) return 'male';
    else if ($P1 == 2) return 'female';
}


function UserGroup($P1)
{
    if ($P1 == 0) return 'User';
    else if ($P1 == 1) return 'Moderator';
    else if ($P1 == 2) return 'Admin';
    else if ($P1 == -1) return 'Banned';
}


function UAccess($P1)
{
    if ($_SESSION['USER_GROUP'] < $P1) MessageSend(1, 'U dont have rule.', '/');
}


function RandomString($P1)
{
    $String = "";
    $Char = '0123456789abcdefghijklmnopqrstuvwxyz';
    for ($i = 0; $i < $P1; $i++)
        $String .= $Char[rand(0, strlen($Char) - 1)];
    return $String;
}

function HideEmail($P1)
{
    $Explode = explode('@', $P1);
    return $Explode[0] . '@*****';
}

//function for xss protect
function FormChars($P1)
{
    return nl2br(htmlspecialchars(trim($P1), ENT_QUOTES), "UTF-8");
}


function GenPass($P1, $P2)
{
    return md5('GENPASS' . md5('321' . $P1 . '123') . md5('678' . $P2 . '890'));
}

function Head($P1)
{
    echo '    <!DOCTYPE html>
              <html>
              <head>
                    <meta charset="utf-8" />
                    <title>' . $P1 . '</title>
                    <meta name="keywords" content="" />
                    <meta name="description" content="" />
                    <link href="/resource/style.css" rel="stylesheet">
                    <link rel="icon" href="/resource/img/favicon.ico" type="image/x-icon">
               </head>';
}

function ModuleID($P1)
{
    if ($P1 == 'news') return 1;
    else if ($P1 == 'loads') return 2;
    else MessageSend(1, 'Module not found.', '/');
}


//number page

function PageSelector($P1, $P2, $P3, $P4 = 5)
{
    $Page = ceil($P3[0] / $P4);
    if ($Page > 1)
    {
        echo '<div class="PageSelector">';
        for ($i = ($P2 - 3); $i < ($Page + 1); $i++)
        {
            if ($i > 0 and $i <= ($P2 + 3))
            {
                if ($P2 == $i) $Swch = 'SwchItemCur';
                else $Swch = 'SwchItem';
                echo '<a class="' . $Swch . '" href="' . $P1 . $i . '">' . $i . '</a>';
            }
        }
        echo '</div>';
    }
}


function MiniIMG($P1, $P2, $P3, $P4, $P5 = 50)
{
    /*
    $p1 - path to picture.
    $p2 - path to little picture.
    $p3 - long.
    $p4 - hight.
    $p5 - quality.
    */
    $Scr = imagecreatefromjpeg($P1);
    $Size = getimagesize($P1);
    $Tmp = imagecreatetruecolor($P3, $P4);
    imagecopyresampled($Tmp, $Scr, 0, 0, 0, 0, $P3, $P4, $Size[0], $Size[1]);
    imagejpeg($Tmp, $P2, $P5);
    imagedestroy($Scr);
    imagedestroy($Tmp);
}


function AdminMenu()
{
    echo '<div class="MenuHead">
                <a href="/admin"><div class="Menu">Main</div></a>
                <a href="/admin/query/logout/1">
                    <div class="Menu">
                        Exit
                    </div>
                </a>                
          </div>';
}


function Menu()
{
        if (!isset($_SESSION['USER_LOGIN_IN'])) $_SESSION['USER_LOGIN_IN'] = 0;

        if ($_SESSION['USER_LOGIN_IN'] != 1)
            $Menu =
                '<a href="/register"><div class="Menu">Registration</div></a>
                <a href="/login"><div class="Menu">LogIn</div></a>';
        else $Menu =
            '<a href="/profile">
            <div class="Menu">
                Profile
            </div>
        </a> ';


    echo '<div class="MenuHead">
                <a href="/news">
                    <div class="Menu">
                        News
                    </div>
                </a>' . $Menu . '
          </div>';
}

function Footer()
{

}

?>