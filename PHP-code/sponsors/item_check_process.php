<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

$drama_name = $_SESSION['post']['drama_name'];
$image = $_SESSION['post']['image'];
$playhouse_name = $_SESSION['post']['playhouse_name'];
$playhouse_info = $_SESSION['post']['playhouse_info'];
$seat_number = $_SESSION['post']['seat_number'];
$play_day = $_SESSION['post']['play_day'];
$ticket_price = $_SESSION['post']['ticket_price'];
$storylines = $_SESSION['post']['storylines'];
$message = $_SESSION['post']['message'];

try {
    $data = array(
        'sponsor_id' => $_SESSION['userinfo']['id'],
        'play_day' => $play_day,
        'playhouse_name' => $playhouse_name,
        'playhouse_info' => $playhouse_info,
        'seat_number' => $seat_number,
        'image' => $image,
        'drama_name' => $drama_name,
        'ticket_price' => $ticket_price,
        'storylines' => $storylines,
        'message' => $message
    );

    // インスタンスを生成する
    $dramadb = new DramaItemsModel;

    $dramadb->registerDramaItem($data);

    $dramaId = $dramadb->lastInsertId();

    $seatData = array(
        'drama_id' => $dramaId,
        'playhouse_name' => $playhouse_name,
        'residue_seat_number' => $seat_number
    );

    $ddb = new DramaItemsModel;

    $ddb->registerSeatNumber($seatData);

    //セッションに保存されているpostデータを削除する
    unset($_SESSION['post']);

    header('Location: ./item_thanks.php');
    exit;
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
