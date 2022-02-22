<?php
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

// セッションスタート
SessionUtil::sessionStart();

// お客様ログイン済みのとき
if (isset($_SESSION['userinfo'])) {
    $userinfo = $_SESSION['userinfo'];
} elseif (!isset($_SESSION['userinfo'])) {
    header('Location: ../top/top_1.php');
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>公演情報確定画面</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="./item_check.css">
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
            <!-- /ヘッダー -->
            <!-- カードボディ -->
            <div class="container">
                <div class="col-10 mx-auto">
                    <div class="card-body">
                        <div class="title" style="text-align: center;">
                            <u><b><?= $_SESSION['post']['drama_name'] ?></b></u>
                        </div>
                        <br>
                        <div class="image" style="text-align: center;">
                            <img src="../image/<?= $_SESSION['post']['image'] ?>" alt="公演画像" width="200px">
                        </div>
                        <br><br><br>
                        <div class="sponsor">
                            <p>劇団名:<?= $userinfo['company_name'] ?></p>
                        </div>
                        <div class="playday">
                            <p>公演期間:<?= $_SESSION['post']['play_day'] ?></p>
                        </div>
                        <div class="ticket_price">
                            <p>チケット価格:<?= $_SESSION['post']['ticket_price'] ?>円</p>
                        </div>
                        <div class="seat-number">
                            <p>総座席数:<?= $_SESSION['post']['seat_number'] ?>席</p>
                        </div>
                        <div class="playhouse">
                            <p>会場:<?= $_SESSION['post']['playhouse_name'] ?></p>
                        </div>
                        <div class="playhouse-info">
                            <b>会場情報</b>
                            <p class="location-info"><?= $_SESSION['post']['playhouse_info'] ?></p>
                        </div>
                        <br>
                        <div class="storylines">
                            <b>あらすじ</b><br>
                            <p class="location-info"><?= $_SESSION['post']['storylines'] ?></p>
                        </div>
                        <br>
                        <div class="message">
                            <b>メッセージ</b><br>
                            <p class="location-info"><?= $_SESSION['post']['message'] ?></p>
                        </div>
                        <br>
                        <div class="font-weight-bold" style="color: red;">
                            ※確定後の修正はできません。ご注意ください。
                        </div>
                        <br>
                        <div class="last_check" style="text-align: right;">
                            <input type="button" value="戻る" class="btn btn-outline-warning" onclick="location.href='./sponsors_items.php';">
                            <input type="button" value="確定する" class="btn btn-primary" onclick="location.href='./item_check_process.php';">
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