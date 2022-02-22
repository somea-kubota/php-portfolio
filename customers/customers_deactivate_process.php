<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

date_default_timezone_set("Asia/Tokyo");

//セッションスタート
SessionUtil::sessionStart();

$post = SaftyUtil::sanitaize($_POST);

// Sessionに入っていたらリセットする
if (isset($_SESSION['msg']['err'])) {
    unset($_SESSION['msg']['err']);
}

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./customers_deactivate.php');
    exit;
}

try {
    $Customerdb = new CustomersModel();

    $dramadate = $Customerdb->getOrderHistory($post['id']);

    foreach ($dramadate as $value) {
        // もしも、注文された公演の日付けが今日以降だった場合は退会不可
        if ($value['play_day'] > date('Y-m-d')) {
            $_SESSION['msg']['err'] = '今日以降に開催される公演があります。';
            header('Location: ./customers_deactivate.php');
        }
    }
    $Customerdb->delete($_POST['id']);
    header('Location:../top/top_2.php');
    exit;
} catch (Exception $e) {
    var_dump($e);
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
