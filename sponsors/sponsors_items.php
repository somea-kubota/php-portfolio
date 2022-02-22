<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

SessionUtil::sessionStart();

unset($_SESSION['post']['drama_name']);
unset($_SESSION['post']['image']);
unset($_SESSION['post']['playhouse_name']);
unset($_SESSION['post']['playhouse_info']);
unset($_SESSION['post']['seat_number']);
unset($_SESSION['post']['play_day']);
unset($_SESSION['post']['ticket_price']);
unset($_SESSION['post']['storylines']);
unset($_SESSION['post']['message']);

if (empty($_SESSION['userinfo'])) {
    //未ログインのとき
    header('Location: ./sponsors_login.php');
} else {
    $userinfo = $_SESSION['userinfo'];
}

try {
    // Sponsorsテーブルクラスのインスタンスを生成する
    $Sponsorsdb = new SponsorsModel();

    $Sponsor = $Sponsorsdb->selectAll($userinfo['id']);
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
    <title>公演情報入力</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="./items.css">
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
                    <div class="col-sm-3 mt-3 mb-3">公演情報登録ページ</div>
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
                <!-- 入力フォーム -->
                <div class="row my-2">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <form action="./sponsors_items_process.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                            <input type="hidden" name="id" id="id" value="<?= $userinfo['id'] ?>">
                            <div class="form-group">
                                <label for="drama_name">タイトル</label>
                                <input type="text" class="form-control" id="drama_name" name="drama_name">
                                <?php if (isset($_SESSION['drama_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['drama_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="image">画像(jpeg,jpgがアップロード可能です)</label>
                                <input type="file" name="image" id="image" accept="image/jpeg">
                                <?php if (isset($_SESSION['image_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['image_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="play_day">公演日程</label>
                                <input type="date" class="form-control" name="play_day" id="play_day">
                                <?php if (isset($_SESSION['play_day_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['play_day_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="playhouse_name">会場名</label>
                                <input type="text" id="playhouse_name" name="playhouse_name" class="form-control">
                                <?php if (isset($_SESSION['playhouse_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['playhouse_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="playhouse_info">会場場所の詳細（住所等）</label>
                                <textarea id="playhouse_info" name="playhouse_info" class="form-control" rows="5"></textarea>
                                <?php if (isset($_SESSION['playhouse_info_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['playhouse_info_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="seat_number">座席数</label>
                                <input type="text" id="seat_number" name="seat_number" class="form-control">席
                                <?php if (isset($_SESSION['seat_number_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['seat_number_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if (isset($_SESSION['seat_number_err2'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['seat_number_err2'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="ticket_price">チケット価格</label>
                                <input type="text" class="form-control" name="ticket_price" id="ticket_pruce">円
                                <?php if (isset($_SESSION['ticket_price_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['ticket_price_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if (isset($_SESSION['ticket_price_err2'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['ticket_price_err2'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="storylines">あらすじ</label>
                                <textarea class="form-control" name="storylines" id="storylines" rows="8"></textarea>
                                <?php if (isset($_SESSION['storylines_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['storylines_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="message">メッセージ</label>
                                <textarea class="form-control" name="message" id="message" rows="8"></textarea>
                                <?php if (isset($_SESSION['message_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['message_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="text_right">
                                <input type="button" value="登録情報確認" class="btn btn-primary" onclick="submit();">
                                <input type="button" value="キャンセル" class="btn btn-outline-primary" onclick="location.href='./index.php';">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
                <!-- 入力フォーム ここまで -->
            </div>
            <!-- コンテナ ここまで -->
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