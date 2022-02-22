<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

SessionUtil::sessionStart();

if (empty($_SESSION['userinfo'])) {
    //未ログインのとき
    header('Location: ./sponsors_login.php');
} else {
    $userinfo = $_SESSION['userinfo'];
}

try {
    $dramaitemsdb = new DramaItemsModel();

    $dramahis = $dramaitemsdb->getDramaItemHistoryById($userinfo['id']);
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
    <title>登録済み公演</title>
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
                            <a class="nav-link text-white" href="../sponsors/index.php">会員ページ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../dramas/old_dramas.php">過去公演</a>
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link text-white" href="../sponsors/sponsors_logout_process.php">ログアウト</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- ナビゲーション ここまで -->
            <!-- コンテナ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">公開済み公演確認</div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3"></div>
                </div>
                <!-- カードボディ -->
                <div class="row my-2">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <?php foreach ($dramahis as $value) : ?>
                                        <tr>
                                            <th scope="row"></th>
                                            <div class="drama-all">
                                                <td>
                                                    <div class="image center">
                                                        <img src="../image/<?= $value['image'] ?>" alt="公演画像" width="200">
                                                    </div>
                                                    <br>
                                                    <div class="dramainfo">
                                                        <div class="title">
                                                            <b><?= $value["drama_name"] ?></b>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="sponsor">
                                                            劇団名:<?= $value['company_name'] ?>
                                                        </div>
                                                        <div class="playday">
                                                            公演期間:<?= $value['play_day'] ?>
                                                        </div>
                                                        <div class="ticket_price">
                                                            チケット価格:<?= $value['ticket_price'] ?>円
                                                        </div>
                                                        <div class="seat-number">
                                                            総座席数:<?= $value['seat_number'] ?>席
                                                        </div>
                                                        <div class="playhouse">
                                                            会場:<?= $value['playhouse_name'] ?>
                                                        </div>
                                                        <div class="playhouse-info">
                                                            会場情報:<?= $value['playhouse_info'] ?>
                                                        </div>
                                                        <br>
                                                        <div class="storylines">
                                                            <b>あらすじ</b><br>
                                                            <?= $value['storylines'] ?>
                                                        </div>
                                                        <br>
                                                        <div class="message">
                                                            <b>メッセージ</b><br>
                                                            <?= $value['message'] ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </td>
                                            </div>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-"></div>
                </div>
                <!-- /カードボディ -->
            </div>
            <!-- /コンテナ -->
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