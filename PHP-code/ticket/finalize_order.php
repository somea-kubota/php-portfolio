<?php
require_once('../class/db/BaseModel.php');
require_once('../class/config/Config.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/ValidationUtil.php');
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

    $carts = $customerorderdb->getCartItem($userinfo['id']);

    $ticket_num = 0;
    foreach ($carts as $cart) {
        $ticket_num += $cart['count'];
    }

    $ticket_total = 0;
    foreach ($carts as $price) {
        $ticket_total += $price['ticket_price'] * $price['count'];
    }

    $dramadb = new DramaItemsModel();

    $ticketCount = $dramadb->ticketCount($_POST);
} catch (Exception $e) {
    // // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>カート</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../ticket/cart.css">
</head>
<style>
    body {
        background-image: url(../image/kyle-head-p6rNTdAPbuk-unsplash.jpg);
        background-repeat: no-repeat
    }
</style>

<body>
    <div class="col-sm-2"></div>
    <div class="col-sm-8 mx-auto">
        <div class="card mt-3 mb-3">
            <!-- ヘッダー -->
            <div class="card-header" style="background-color: darkred;">
                <nav class="navbar" style="background-color: darkred;">
                    <a class="navbar-brand text-white" href="../top/top_2.php">ちけっと</a>
                    <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link text-white" href="../top/top_2.php">トップページ <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../customers/index.php">会員ページ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../dramas/old_dramas.php">過去公演</a>
                            </li>
                            <li class="nav-item text-white">
                                <a class="nav-link text-white" href="../customers/customers_logout_process.php">ログアウト</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="caed-body">
                    <div class="row my-2">
                        <div class="col-sm-3 mt-3 mb-3">購入確認画面</div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-3"></div>
                    </div>
                    <!-- エラーメッセージ -->
                    <?php if (isset($_SESSION['msg']['err'])) : ?>
                        <div class="row my-2">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                                <?= $_SESSION['msg']['err'] ?><button class="close" data-dismiss="alert">&times;</button>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                    <?php endif ?>
                    <!-- エラーメッセージ ここまで -->
                    <div class="basket">
                        <div class="row my-2">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <form action="./finalize_order_process.php" method="post">
                                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                                    <input type="hidden" name="customerid" id="customerid" value="<?= $userinfo['id'] ?>">
                                    <table>
                                        <thead>
                                            <div class="basket-labels">
                                                <tr>
                                                    <th class="item">公演情報</th>
                                                    <th class="ticket_price">チケット価格</th>
                                                    <th class="ticket_num">枚数</th>
                                                    <th class="subtotal">合計価格</th>
                                                </tr>
                                            </div>
                                        </thead>
                                        <tbody>
                                            <div class="item-details">
                                                <?php foreach ($carts as $value) : ?>
                                                    <tr>
                                                        <td>
                                                            <div class="item">
                                                                <div class="product-details">
                                                                    <strong>
                                                                        <span class="drama_name">
                                                                            <?= $value['drama_name'] ?>
                                                                        </span>
                                                                    </strong>
                                                                    <p>
                                                                        日程:<?= $value['play_date'] ?>
                                                                    </p>
                                                                    <p>
                                                                        劇場:<?= $value['playhouse_name'] ?>
                                                                    </p>
                                                                    <p>
                                                                        劇団:<?= $value['company_name'] ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="ticket_price price" style="text-align: center;">
                                                                <?= $value['ticket_price'] ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="quantity" style="text-align: center;">
                                                                <?= $value['count'] ?>枚
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="subtoal subtotal-total" style="text-align: center;">
                                                                <?php $price2 = (int)$value['ticket_price']; ?>
                                                                <?php $ticket_count = (int)$value['count'] ?>
                                                                <?= $price2 * $ticket_count ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </div>
                                        </tbody>
                                    </table>
                                    <br><br>
                                    <div class="customer-check">
                                        <div class="send-add" style="text-align: left;">
                                            <strong>郵送先</strong>
                                            <br>
                                            <div class="customer-data">
                                                <label for="family_name">姓</label>
                                                <?= $userinfo['family_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="first_name">名</label>
                                                <?= $userinfo['first_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="postal_code">郵便番号</label>
                                                <?= $userinfo['postal_code'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="prefecture_name">県名</label>
                                                <?= $userinfo['prefecture_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="city_name">市・区名</label>
                                                <?= $userinfo['city_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="town_name">町・村名</label>
                                                <?= $userinfo['town_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="building_name">建物名・番号等</label>
                                                <?= $userinfo['building_name'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="phone_number">電話番号</label>
                                                <?= $userinfo['phone_number'] ?>
                                            </div>
                                            <div class="customer-data">
                                                <label for="email">メールアドレス</label>
                                                <?= $userinfo['email'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="summary">
                                        <div class="summary-total-items"><span class="total-items"></span>
                                            <div class="total-title" style="text-align: left;">合計枚数</div>
                                            <div class="total-count" id="basket-count" style="text-align: right;">
                                                <?= $ticket_num ?>枚
                                            </div>
                                        </div>
                                        <div class="summary-total">
                                            <div class="total-title">合計価格</div>
                                            <div class="total-value final-value" id="basket-total">
                                                <?= $ticket_total ?>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="summary-checkout">
                                            <button class="checkout-cta" type="submit">
                                                購入確定
                                            </button>
                                        </div>
                                        <div class="danger" style="color: red;">
                                            <p>※購入確定後はキャンセルできません</p>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="return">
                                    <input class="btn btn-success ml-auto d-block" type="button" value="戻る" onclick="location.href='./cart.php';">
                                </div>
                                <br>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /コンテナ -->
            <!-- フッター -->
            <div class="card-footer text-right">
                <p>&copy;A.Kubota</p>
            </div>
            <!-- /フッター -->
        </div>
    </div>
    <div class="col-sm-2"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>