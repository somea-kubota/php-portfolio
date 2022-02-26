<?php
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/config/Config.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/SaftyUtil.php');

// セッションスタート
SessionUtil::sessionStart();

// 既に設定済みのセッションに保存されたPOSTデータを削除
unset($_SESSION['post']);

// エラーメッセージを削除
if (isset($_SESSION['msg']['err'])) {
    unset($_SESSION['msg']['err']);
}

if (empty($_SESSION['userinfo'])) {
    header('Location: ../customers/customers_login.php');
    exit;
} else {
    // ログイン済みのとき
    $userinfo = $_SESSION['userinfo'];
}

try {
    $Customerdb = new CustomersModel;

    $customerinfo = $Customerdb->getCustomerById($userinfo['id']);

    $carts = $Customerdb->getCartItem($userinfo['id']);
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
    <title>お客様会員ページ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
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
                <!-- ナビゲーション -->
                <nav class="navbar navbar-light" style="background-color: darkred;">
                    <a class="navbar-brand text-white" href="../top/top_2.php">ちけっと</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="../top/top_2.php">トップページ</a>
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
                <!-- ナビゲーション ここまで -->
            </div>
            <!-- カートのナビバー -->
            <nav class="navbar navbar-light bg-light">
                <button class="btn btn-outline-success my-2 my-sm-0 ml-auto" onclick="location.href='../ticket/cart.php'">
                    カート
                    <span class="fs-p-cartItemNumber fs-client-cart-count fs-clientInfo is-ready fs-client-cart-count--0">
                        <?= count($carts); ?>
                    </span>
                </button>
            </nav>
            <!-- /カートのナビバー -->
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 mx-auto">
                        <div class="mt-3">
                            <br>
                            <p class="card-title text-center"><?= $customerinfo['family_name'] . $customerinfo['first_name'] ?>様の会員ページ</p>
                            <div style="display: flex;" class="col-md-10 mx-auto">
                                <ul class="navbar-nav mx-auto text-center">
                                    <form action="./customers_order_history.php" method="post" class="btn btn-primary my-5 mx-auto">
                                        <input type="hidden" name="token" value="<?php SaftyUtil::generateToken() ?>">
                                        <input type="hidden" name="id" value="<?php $customerinfo['id'] ?>" class="btn btn-primary">
                                        <input type="button" value="購入履歴" class="btn btn-primary" onclick="submit();">
                                    </form>
                                    <form action="./customers_edit_acount.php" method="post" class="btn btn-primary my-5 mx-auto">
                                        <input type="hidden" name="token" value="<?php SaftyUtil::generateToken() ?>">
                                        <input type="hidden" name="id" value="<?php $customerinfo['id'] ?>" class="btn btn-primary">
                                        <input type="button" value="会員情報修正" class="btn btn-primary" onclick="submit();">
                                    </form>
                                    <form action="./customers_deactivate.php" method="post" class="btn btn-primary my-5 mx-auto">
                                        <input type="hidden" name="token" value="<?php SaftyUtil::generateToken() ?>">
                                        <input type="hidden" name="id" value="<?php $customerinfo['id'] ?>" class="btn btn-primary">
                                        <input type="button" value="退会" class="btn btn-primary" onclick="submit();">
                                    </form>
                                </ul>
                            </div>
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

    <!-- 必要なJavascriptを読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>