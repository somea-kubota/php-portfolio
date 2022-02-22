<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($_POST['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./');
    exit;
}

// Sessionに入っていたらリセットする
if (isset($_SESSION['msg']['err'])) {
    unset($_SESSION['msg']['err']);
}

try {
    $Sponsordb = new SponsorsModel();

    $Sponsordb->delete($_POST['id']);
    header('Location:../top/top_1.php');
    exit;
} catch (Exception $e) {
    var_dump($e);
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
