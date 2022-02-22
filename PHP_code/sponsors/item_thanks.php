<?php
require_once('../class/db/BaseModel.php');
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

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>公演情報登録ありがとうございます</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="./cart.css">
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
                <!-- ログインしていた場合のナビバー -->
                <?php if (isset($userinfo['email'])) { ?>
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
                                    <a class="nav-link text-white" href="../playhouse/play_information.php">劇場情報</a>
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
                                    <a class="nav-link text-white" href="../sponsors/sponsors_login.php">主催者様ログイン</a>
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
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="caed-body">
                    <div class="row my-2">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 mt-3">
                            <div class="thanks" style="text-align: center;">
                                <strong><?= $userinfo['company_name'] ?>様</strong>
                                <strong>公演のご登録ありがとうございました。</strong>
                            </div>
                            <br><br><br>
                            <div class="to-top">
                                <input class="btn btn-primary ml-auto d-block" type="button" value="会員ページへ戻る" onclick="location.href='../sponsors/index.php';">
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
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