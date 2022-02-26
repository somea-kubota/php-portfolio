<?php
require_once('../class/config/Config.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

// セッション変数に保存したPOSTデータ
$userinfo = "";
if (!empty($_SESSION['post']['email'])) {
    $userinfo = $_SESSION['post']['email'];
}

// セッション変数に保存したPOSTデータを削除
unset($_SESSION['post']);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主催者ログイン</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<style>
    body {
        background-image: url(../image/kyle-head-p6rNTdAPbuk-unsplash.jpg);
        background-repeat: no-repeat
    }
</style>

<body>
    <div class="col-sm-3"></div>
    <div class="col-sm-6 mx-auto">
        <div class="card mt-3 mb-3">
            <!-- ナビゲーション -->
            <nav class="navbar" style="background-color: darkred;">
                <a class="navbar-brand text-white text-center" href="../top/top_1.php" style="background-color: darkred;">ちけっと</a>
                <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link text-white" href="../top/top_1.php">トップページ <span class="sr-only">(current)</span></a>
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
            <!-- /ナビゲーション -->
            <!-- コンテナー -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">主催者様ログイン</div>
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
                <div class="row my-2">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <form action="./sponsors_login_process.php" method="post">
                            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                            <div class="form-group">
                                <label for="email">メールアドレス</label>
                                <input type="text" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="password">パスワード</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary">ログイン</button>
                        </form>

                    </div>
                    <div class="col-sm-3"></div>
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
    <div class="col-sm-3"></div>

    <!-- 必要なJavascriptを読み込む -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>