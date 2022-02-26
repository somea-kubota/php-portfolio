<?php
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/config/Config.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/db/DramaItemsModel.php');


// セッションスタート
SessionUtil::sessionStart();

// エラーメッセージを削除
unset($_SESSION['msg']['err']);

// ログイン済みのとき
if (isset($_SESSION['userinfo'])) {
    $userinfo = $_SESSION['userinfo'];
}

try {
    if (isset($userinfo)) {
        // Customersテーブルクラスのインスタンスを生成する
        $Customersdb = new CustomersModel();

        // レコードを取得する
        $Customer = $Customersdb->getCustomerById($userinfo['id']);

        $carts = $Customersdb->getCartItem($userinfo['id']);
    }

    // dramasテーブルクラスのインスタンスを生成する
    $db = new DramaItemsModel();

    // レコードを全件取得する（期限日の新しいものから並び替える）
    $list = $db->getDramaItemAll();
} catch (Exception $e) {
    var_dump($e);
    exit;
    // // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}

date_default_timezone_set("Asia/Tokyo");

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>過去公演情報</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../dramas/old_dramas.css">
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
                <?php if (isset($_SESSION['userinfo'])) { ?>
                    <!-- ログインしていた場合のナビバー -->
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
                                    <?php if (isset($_SESSION['userinfo']['company_name'])) { ?>
                                        <a class="nav-link text-white" href="../sponsors/index.php">会員ページ</a>
                                    <?php } else { ?>
                                        <a class="nav-link text-white" href="../customers/index.php">会員ページ</a>
                                    <?php } ?>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../dramas/old_dramas.php">過去公演</a>
                                </li>
                                <li class="nav-item text-white">
                                    <?php if (isset($_SESSION['userinfo']['company_name'])) { ?>
                                        <a class="nav-link text-white" href="../sponsors/sponsors_logout_process.php">ログアウト</a>
                                    <?php } else { ?>
                                        <a class="nav-link text-white" href="../customers/customers_logout_process.php">ログアウト</a>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <!-- /ログインしていた場合のナビバー -->
                <?php } else { ?>
                    <!-- ログインしていない場合のナビバー -->
                    <nav class="navbar" style="background-color: darkred;">
                        <a class="navbar-brand text-white text-center" href="../top/top_2.php" style="background-color: darkred;">ちけっと</a>
                        <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link text-white" href="../top/top_2.php">トップページ <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../customers/customers_login.php">お客様ログイン</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../sponsors//sponsors_login.php">主催者様ログイン</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../customers/customers_resistration.php">お客様新規会員登録はこちらから</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../sponsors/sponsors_resistration.php">主催者様新規会員登録はこちらから</a>
                                </li>
                                <li class="nav-item text-white">
                                    <a class="nav-link text-white" href="../dramas/old_dramas.php">過去公演</a>
                                </li>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <!-- /ログインしていない場合のナビバー -->
                <?php } ?>
            </div>
            <!-- 検索、カートのナビバー -->
            <nav class="navbar navbar-light bg-light">
                <p>日付けで検索する場合はハイフンで入力してください。(例:2021-01-09)</p>
                <form class="form-inline" action="../search/serch.php" method="post">
                    <input class="form-control mr-sm-2" type="search" placeholder="公演検索" aria-label="Search" name="search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索</button>
                </form>
                <?php if (isset($_SESSION['userinfo']['first_name'])) : ?>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="location.href='../ticket/cart.php'">
                        カート
                        <span class="fs-p-cartItemNumber fs-client-cart-count fs-clientInfo is-ready fs-client-cart-count--0"></span>
                        <?= count($carts); ?>
                    </button>
                <?php endif ?>
            </nav>
            <!-- /検索、カートのナビバー -->
            <!-- /ヘッダー -->
            <!-- ボディ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">過去公演情報一覧</div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3"></div>
                </div>
                <!-- 過去の日付けのみ表示 -->
                <?php foreach ($list as $value) {
                    if ($value['play_day'] > date('Y-m-d')) {
                        $class = ' class="display-none"';
                    } else {
                        $class = ' class="my-3 mx-3"';
                    } ?>
                    <div <?= $class ?>>
                        <div class="product" style="text-align: center;">
                            <img src="../image/<?= $value['image'] ?>" alt="公演画像" class="product-frame mx-3 my-3" width="200">
                            <br>
                            <br>
                            <h1><b><?= $value['drama_name'] ?></b></h1>
                            <br>
                            <br>
                            <p>公演期間:<?= $value['play_day'] ?></p>
                            <br>
                            <p>会場:<?= $value['playhouse_name'] ?></p>
                            <br>
                            <p>劇団名:<?= $value['company_name'] ?></p>
                            <br>
                        </div>
                        <br>
                        <div class="border"  style="border-bottom: 2px solid gray;"></div>
                    </div>
                <?php } ?>
            </div>
            <!-- /ボディ -->
            <!-- カードフッター -->
            <div class="card-footer text-right">
                <p class="lead">&copy;kubota</p>
            </div>
            <!-- /カードフッター -->
        </div>
    </div>
    <div class="col-sm-2"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>