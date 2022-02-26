<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

//セッションスタート
SessionUtil::sessionStart();

//サニタイズ
$post = SaftyUtil::sanitaize($_POST);

$image = $_FILES['image'];

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./sponsors_items.php');
    exit;
}

// POSTされてきた値をセッション変数に保存する
$_SESSION['post']['drama_name'] = $post['drama_name'];
$_SESSION['post']['image'] = $image['name'];
$_SESSION['post']['playhouse_name'] = $post['playhouse_name'];
$_SESSION['post']['playhouse_info'] = $post['playhouse_info'];
$_SESSION['post']['seat_number'] = $post['seat_number'];
$_SESSION['post']['play_day'] = $post['play_day'];
$_SESSION['post']['ticket_price'] = $post['ticket_price'];
$_SESSION['post']['storylines'] = $post['storylines'];
$_SESSION['post']['message'] = $post['message'];


try {
    $checkFlag = true;
    $checkDramaNameFlag = true;
    $checkDramaImageFlag = true;
    $checkPlayHouseNameFlag = true;
    $checkPlayHouseInfoFlag = true;
    $checkSeatNumberFlag = true;
    $checkPlayDayFlag = true;
    $checkTicketPriceFlag = true;
    $checkStorylinesFlag = true;
    $checkMessageFlag = true;
    $checkTicketPriceFlag2 = true;
    $checkSeatNumberFlag2 = true;

    //項目デーション
    if (!ValidationUtil::isValidDramaName($post['drama_name'])) {
        $_SESSION['drama_name_err'] = 'タイトルは' . Config::MSG_WORD_COUNT2;
        $checkDramaNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['drama_name_err']);
    }
    if (!ValidationUtil::isValidDramaImage($image['size'])) {
        $_SESSION['size_err'] = 'ファイルを選択してください。';
        $checkDramaImageFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['size_err']);
    }
    if (!ValidationUtil::isValidPlayhouseName($post['playhouse_name'])) {
        $_SESSION['playhouse_name_err'] = '会場名を入力してください。';
        $checkPlayHouseNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['playhouse_name_err']);
    }
    if (!ValidationUtil::isValidPlayhouseName($post['playhouse_info'])) {
        $_SESSION['playhouse_info_err'] = '会場の詳細情報を入力してください。';
        $checkPlayHouseInfoFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['playhouse_info_err']);
    }
    if (!ValidationUtil::isValidSeatNumber($post['seat_number'])) {
        $_SESSION['seat_number_err'] = '座席数を入力してください。';
        $checkSeatNumberFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['seat_number_err']);
    }
    if (preg_match("/[^0-9]/", $post['seat_number'])) {
        $_SESSION['seat_number_err2'] = "数字以外が入力されています";
        $checkSeatNumberFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['seat_number_err2']);
    }
    if (!ValidationUtil::isValidPlayDay($post['play_day'])) {
        $_SESSION['play_day_err'] = '日付けを選択してください。';
        $checkPlayDayFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['play_day_err']);
    }
    if (!ValidationUtil::isValidTicketPrice($post['ticket_price'])) {
        $_SESSION['ticket_price_err'] = 'チケット価格は' . Config::MSG_WORD_COUNT2;
        $checkTicketPriceFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['ticket_price_err']);
    }
    if (preg_match("/[^0-9]/", $post['ticket_price'])) {
        $_SESSION['ticket_price_err2'] = "数字以外が入力されています";
        $checkTicketPriceFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['ticket_price_err2']);
    }
    if (!ValidationUtil::isValidStorylines($post['storylines'])) {
        $_SESSION['storylines_err'] = 'あらすじは' . Config::MSG_WORD_COUNT7;
        $checkStorylinesFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['storylines_err']);
    }
    if (!ValidationUtil::isValidMessage($post['message'])) {
        $_SESSION['message_err'] = 'メッセージは' . Config::MSG_WORD_COUNT7;
        $checkMessageFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['message_err']);
    }

    if (
        $checkFlag
        && $checkDramaNameFlag
        && $checkDramaImageFlag
        && $checkPlayHouseNameFlag
        && $checkPlayHouseInfoFlag
        && $checkSeatNumberFlag
        && $checkPlayDayFlag
        && $checkTicketPriceFlag
        && $checkStorylinesFlag
        && $checkMessageFlag
        && $checkTicketPriceFlag2
        && $checkSeatNumberFlag2
    ) {

        unset($_SESSION['msg']['err']);
        unset($_SESSION['drama_name_err']);
        unset($_SESSION['image_err']);
        unset($_SESSION['playhouse_name_err']);
        unset($_SESSION['playhouse_Info_err']);
        unset($_SESSION['seat_number_err']);
        unset($_SESSION['seat_number_err2']);
        unset($_SESSION['play_day_err']);
        unset($_SESSION['ticket_price_err']);
        unset($_SESSION['ticket_price_err2']);
        unset($_SESSION['storylines_err']);
        unset($_SESSION['message_err']);

        //画像をimageフォルダにアップロードする
        $path = '../image/';

        move_uploaded_file($image['tmp_name'], $path.$image['name']);

        header('Location: ./item_check.php');
        exit;
    } else {
        header('Location: ./sponsors_items.php');
        exit;
    }
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
