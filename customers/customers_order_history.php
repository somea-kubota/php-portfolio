<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/SaftyUtil.php');

//セッションスタート
SessionUtil::sessionStart();

if (!isset($_SESSION['userinfo'])) {
    //未ログインのとき
    header('Location: ../customers/customers_login.php');
} else {
    $userinfo = $_SESSION['userinfo'];
}

try {
    $customersdb = new CustomersModel;

    $orders = $customersdb->getOrderHistory($userinfo['id']);
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="customers_order_history.css">
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
                <!-- ナビバー -->
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
                <!-- /ナビバー -->
            </div>
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">購入履歴</div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3"></div>
                </div>
                <?php if (!empty($orders)) { ?>
                    <table class="table">
                        <tbody>
                            <?php foreach ($orders as $value) : ?>
                                <tr>
                                    <th scope="row"></th>
                                    <td><img src="../image/<?= $value['image'] ?>" alt="公演画像" width="200">
                                    </td>
                                    <td>
                                        <h1><b><?= $value['drama_name'] ?></b></h1>
                                        <br>
                                        <br>
                                        <p>予約日程：<?= $value['play_day'] ?></p>
                                        <br>
                                        <p>会場：<?= $value['playhouse_name'] ?></p>
                                        <br>
                                        <p>劇団名：<?= $value['company_name'] ?></p>
                                        <br>
                                        <p>購入枚数：<?= $value['count'] ?></p>
                                        <br>
                                        <p>合計金額：<?= $value['total_price'] ?></p>
                                        <br>
                                        <p>購入日：<?= $value['order_date'] ?></p>
                                        <br><b>会場情報</b>
                                        <br>
                                        <p class="location-info"><?= $value['playhouse_info'] ?></p>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>購入履歴はありません。</p>
                <?php } ?>
            </div>
            <!-- /コンテナ -->
            <!-- カードフッター -->
            <div class="card-footer text-right">
                <p>&copy;kubota</p>
            </div>
            <!-- /カードフッター -->
        </div>
    </div>
    <div class="col-sm-2"></div>

    <!-- 必要なJavascriptを読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>