<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

if (!isset($_SESSION['userinfo'])) {
    header('Location: ./sponsors_login.php');
    exit;
} else {
    // ログイン済みのとき
    $userinfo = $_SESSION['userinfo'];
}

try {
    // Sponsorsテーブルクラスのインスタンスを生成する
    $Sponsordb = new SponsorsModel();

    $Sponsor = $Sponsordb->getSponsorById($userinfo['id']);
} catch (Exception $e) {
    var_dump($e);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>退会確認</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<style>
    body {
        background-image: url(../image/kyle-head-p6rNTdAPbuk-unsplash.jpg);
        background-repeat: no-repeat
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card mt-3">
                    <div class="card-header text-white text-center" style="background-color: darkred;">
                        <h1>ちけっと</h1>
                    </div>
                    <br>
                    <div class="card-body">
                        ※現在登録中の団体情報は全て削除されます。
                        <br>
                        ※公開済みの公演情報は残ります。
                        <br>
                        <br>
                        <!-- エラーメッセージ -->
                        <?php if (isset($_SESSION['msg']['err'])) : ?>
                            <?php foreach ($_SESSION['msg']['err'] as $err) : ?>
                                <div class="row my-2">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                                        <?= $_SESSION['msg']['err'] ?> <button class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                        <!-- エラーメッセージ ここまで -->
                        <form action="./sponsors_deactivate_process.php" method="post">
                            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                            <input type="hidden" name="id" value="<?= $Sponsor['id'] ?>">
                            <input type="button" value="はい、退会します" class="btn btn-danger" onclick="submit();"><br>
                            <input type="button" value="いいえ、退会しません" class="btn btn-outline-primary" onclick="location.href='./index.php';">
                        </form>
                    </div>
                    <!-- フッター -->
                    <div class="card-footer text-right">
                        <p>&copy;A.Kubota</p>
                    </div>
                    <!-- /フッター -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>