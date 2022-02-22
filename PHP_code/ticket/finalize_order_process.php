<?php
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/config/Config.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

if (!isset($_SESSION['userinfo'])) {
    header('Location: ../customers/customers_login.php');
    exit;
} else {
    // ログイン済みのとき
    $userinfo = $_SESSION['userinfo'];
}

$post = SaftyUtil::sanitaize($_POST);

try {
    $customerorderdb = new CustomersModel();

    $carts = $customerorderdb->getCartItem($post['customerid']);

    $ticket_num = 0;
    foreach ($carts as $cart) {
        $ticket_num += $cart['count'];
    }

    $ticket_total = 0;
    foreach ($carts as $price) {
        $ticket_total += $price['ticket_price'] * $price['count'];
    }

    $cart_num = count($carts);

    foreach ($carts as $cart) {

        $price2 = (int)$cart['ticket_price'];
        $ticket_count = (int)$cart['count'];
        $total_price = $price2 * $ticket_count;

        $orderdata = array(
            'drama_id' => $cart['drama_id'],
            'customer_id' => $post['customerid'],
            'total_price' => $total_price,
            'order_date' => date('Y-m-d'),
            'family_name' => $userinfo['family_name'],
            'first_name' => $userinfo['first_name'],
            'postal_code' => $userinfo['postal_code'],
            'prefecture_name' => $userinfo['prefecture_name'],
            'city_name' => $userinfo['city_name'],
            'town_name' => $userinfo['town_name'],
            'building_name' => $userinfo['building_name'],
            'phone_number' => $userinfo['phone_number']
        );

        $customerorderdb->ordertickets($orderdata);

        $orderId = $customerorderdb->lastInsertId();

        $data = array(
            'order_id' => $orderId,
            'price' => $cart['ticket_price'],
            'count' => $cart['count']
        );

        $customerorderdb->resistrationOrders($data);


        //購入されたチケットの枚数分、座席残数から減らす
        $dramadb = new DramaItemsModel();

        $dramainfo = $dramadb->getDramaItemById($cart['drama_id']);

        $residue = $dramadb->getSeatNum($cart['drama_id']);

        $residue_seat = (int)$residue['residue_seat_number'];
        $newSeatNum = $residue_seat - $ticket_count;

        $numData = array(
            'drama_id' => $cart['drama_id'],
            'playhouse_name' => $dramainfo['playhouse_name'],
            'residue_seat_number' => $newSeatNum
        );

        $dramadb->updateSeatNum($numData);
    }

    //カート内の商品を全て削除する
    $cartdb = new CustomersModel();

    $cartdb->cartDelete($post['customerid']);

    header('Location: ./order_thanks.php');
    exit;
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
