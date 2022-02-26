<?php
require_once('../class/db/BaseModel.php');
require_once('../class/config/Config.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/db/CustomersModel.php');

// セッションスタート
SessionUtil::sessionStart();

// 既に設定済みのセッションに保存されたPOSTデータを削除
unset($_SESSION['post']);

// エラーメッセージを削除
unset($_SESSION['msg']['error']);

// お客様ログイン済みのとき
if (isset($_SESSION['userinfo'])) {
    $userinfo = $_SESSION['userinfo'];
}

// サニタイズ
$post = SaftyUtil::sanitaize($_POST);

try {
    // dramasテーブルクラスのインスタンスを生成する
    $dramaitemsdb = new DramaItemsModel();

    $dramainfo = $dramaitemsdb->getDramaItemById($post['id']);

    $residue_seat = $dramaitemsdb->getSeatNum($post['id']);

    if (isset($userinfo['first_name'])) {
        // customersテーブルのインスタンスを生成する
        $customersorderdb = new CustomersModel();

        $carts = $customersorderdb->getCartItem($userinfo['id']);
    }
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
    <title>公演情報詳細</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="./dramas.css">
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
                                    <?php if (isset($userinfo['company_name'])) { ?>
                                        <a class="nav-link text-white" href="../sponsors/index.php">会員ページ</a>
                                    <?php } else { ?>
                                        <a class="nav-link text-white" href="../customers/index.php">会員ページ</a>
                                    <?php } ?>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="../dramas/old_dramas.php">過去公演</a>
                                </li>
                                <li class="nav-item text-white">
                                    <?php if (isset($userinfo['company_name'])) { ?>
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
                    <button class="btn btn-outline-success my-2 my-sm-0" onclick="location.href='../ticket/cart.php'" style="text-align: raight;">
                        カート
                        <span class="fs-p-cartItemNumber fs-client-cart-count fs-clientInfo is-ready fs-client-cart-count--0"></span>
                        <?= count($carts); ?>
                    </button>
                <?php endif ?>
            </nav>
            <!-- /検索、カートのナビバー -->
            <!-- /ヘッダー -->

            <!-- カードボディ -->
            <div class="container">
                <div class="col-10 mx-auto">
                    <div class="card-body">
                        <div class="title" style="text-align: center;">
                            <u><b><?= $dramainfo['drama_name'] ?></b></u>
                        </div>
                        <br>
                        <div class="image" style="text-align: center;">
                            <img src="../image/<?= $dramainfo['image'] ?>" alt="公演画像" width="70%">
                        </div>
                        <br><br><br>
                        <div class="sponsor">
                            <p>劇団名:<?= $dramainfo['company_name'] ?></p>
                        </div>
                        <div class="playday">
                            <p>公演期間:<?= $dramainfo['play_day'] ?></p>
                        </div>
                        <div class="ticket_price">
                            <p>チケット価格:<?= $dramainfo['ticket_price'] ?>円</p>
                        </div>
                        <div class="seat-number">
                            <p>総座席数:<?= $dramainfo['seat_number'] ?>席</p>
                        </div>
                        <div class="residue_seat text-info">
                            <p>(座席残数:<?= $residue_seat['residue_seat_number'] ?>席)</p>
                        </div>
                        <div class="playhouse">
                            <p>会場:<?= $dramainfo['playhouse_name'] ?></p>
                        </div>
                        <div class="playhouse-info">
                            <b>会場情報</b>
                            <p class="location-info"><?= $dramainfo['playhouse_info'] ?></p>
                        </div>
                        <br>
                        <div class="storylines">
                            <b>あらすじ</b><br>
                            <p class="location-info"><?= $dramainfo['storylines'] ?></p>
                        </div>
                        <br>
                        <div class="message">
                            <b>メッセージ</b><br>
                            <p class="location-info"><?= $dramainfo['message'] ?></p>
                        </div>
                        <br>
                        <div class="buy" style="text-align: right;">
                            <?php if ($dramainfo['play_day'] == date('Y-m-d')) { ?>
                                <p>申し訳ございません。本日公演日のため、購入できません。</p>
                            <?php } ?>
                            <?php if (isset($_SESSION['userinfo']['first_name']) && $dramainfo['play_day'] > date('Y-m-d')) { ?>
                                <form action="../ticket/ticket.php" method="post">
                                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                                    <button type="submit" name="id" value="<?= $_POST['id'] ?>" class="btn btn-warning">
                                        チケット購入はこちらから
                                    </button>
                                </form>
                            <?php } ?>
                            <?php if (!isset($_SESSION['userinfo']['first_name'])) { ?>
                                <form action="../customers/customers_login.php" method="post">
                                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                                    <button type="submit" name="id" value="<?= $_POST['id'] ?>" class="btn btn-warning mt-1">
                                        ログインがまだの方はこちらから
                                    </button>
                                </form>
                                <form action="../customers/customers_resistration.php" method="post">
                                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                                    <button type="submit" name="id" value="<?= $_POST['id'] ?>" class="btn btn-outline-success mt-1">
                                        お客様会員登録がまだの方はこちらから
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /カードボディ -->

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