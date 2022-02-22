<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

SessionUtil::sessionStart();

//サニタイズ
$post = SaftyUtil::sanitaize($_POST);

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./ticket.php');
    exit;
}

// POSTされてきた値をセッション変数に保存する
$_SESSION['post']['play_day'] = $post['play_day'];
$_SESSION['post']['ticket_count'] = $post['ticket_count'];

try {
    // インスタンスを生成する
    $dramadb = new DramaItemsModel;

    // レコードを取得する
    $drama = $dramadb->getDramaItemById($post['id']);

    $checkFlag = true;
    $checkPlayDayFlag = true;
    $checkTicketCountFlag = true;

    // 項目名バリデーション
    if (!ValidationUtil::isValidPlayDay($_POST['play_day'])) {
        $_SESSION['play_day_err'] = '日程を選択してください。';
        $checkPlayDayFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['play_day_err']);
    }
    if (!ValidationUtil::isValidPlayDay($_POST['ticket_count'])) {
        $_SESSION['ticket_count_err'] = 'チケット枚数を選択してください。';
        $checkTicketCountFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['ticket_count_err']);
    }

    if (
        $checkFlag
        && $checkPlayDayFlag
        && $checkTicketCountFlag
    ) {
        unset($_SESSION['err']['msg']);
        unset($_SESSION['play_day_err']);
        unset($_SESSION['ticket_count_err']);

        // データベースに登録する内容を連想配列にする。
        $cartdata = array(
            'drama_id' => $drama['id'],
            'customer_id' => $_SESSION['userinfo']['id'],
            'play_date' => $post['play_day'],
            'count' => $post['ticket_count']
        );

        //インスタンスを生成する
        $Customerdb = new CustomersModel();

        $Customerdb->registerCartItem($cartdata);

        unset($_SESSION['post']);

        header('Location: ./cart.php');
        exit;
    } else {
        header('Location: ./ticket.php');
        exit;
    }
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
