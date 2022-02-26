<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

$post = SaftyUtil::sanitaize($_POST);

try {
    $cartdb = new CustomersModel();

    $cartall = $cartdb->getCartItembyid($post['id']);

    // バリデーション
    if (!ValidationUtil::isValidTicketCount($cartall['count'])) {
        $_SESSION['msg']['err'] = Config::MSG_COUNT;
        header('Location: ./cart.php');
        exit;
    }

    // バリデーションを通過したら、処理を行う。
    // セッション変数に保存したエラーメッセージをクリアする
    if (isset($_SESSION['msg']['err'])) {
        unset($_SESSION['msg']['err']);
    }

    // データベースに登録する内容を連想配列にする。
    $data = array(
        'id' => $post['id'],
        'drama_id' => $cartall['drama_id'],
        'customer_id' => $cartall['customer_id'],
        'play_date' => $cartall['play_date'],
        'count' => $post['count']
    );

    $db = new CustomersModel();
    $db->updateCartItemById($data);

    unset($_SESSION['post']);

    header('Location: ./cart.php');
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
