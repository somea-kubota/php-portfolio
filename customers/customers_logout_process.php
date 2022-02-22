<?php
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();
$_SESSION = array();

if (isset($_COOKIE[session_name()]) == true)
{
    setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();

//login.phpにリダイレクト
header('Location: ../top/top_1.php');
exit;
