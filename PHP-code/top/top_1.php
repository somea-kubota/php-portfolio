<?php
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

//セッションに保存されているログイン情報,会員情報があれば削除する
if (isset($_SESSION['userinfo'])) {
    unset($_SESSION['userinfo']);
}
if (isset($_SESSION['login'])) {
    unset($_SESSION['login']);
}

// エラーがセッションに入っていたらリセットする
unset($_SESSION['msg']['err']);

//ログイン回数をリセットする
unset($_SESSION['login_failure']);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<style>
    body {
        background-image: url(../image/kyle-head-p6rNTdAPbuk-unsplash.jpg);
        background-repeat: no-repeat
    }
</style>

<body>
    <div class="container">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 mx-auto">
            <div class="card mt-3 mb-3">
                <!-- ヘッダー -->
                <div class="card-header" style="background-color: darkred;">
                    <nav class="navbar" style="background-color: darkred;">
                        <div class="navbar-brand text-white">
                            <h1>ちけっと</h1>
                        </div>
                    </nav>
                </div>
                <!-- /ヘッダー -->
                <!-- ボディ -->
                <div class="card-body">
                    <h2 class="text-center mt-3">ようこそ</h2>
                    <br>
                    <br>
                    <div class="text-center">
                        <button onclick="location.href='../customers/customers_login.php'" class="btn btn-primary my-10 mx-10" style="background-color: red;">お客様ログインはこちらから</button>
                        <br>
                        <br>
                        <br>
                        <button onclick="location.href='../sponsors/sponsors_login.php'" class="btn btn-primary my-10 mx-10" style="background-color: green;">主催者様ログインはこちらから</button>
                        <br>
                        <br>
                        <br>
                        <button onclick="location.href='../customers/customers_resistration.php'" class="btn btn-primary my-10 mx-10" style="background-color: #f2cf01;">お客様新規登録はこちらから</button>
                        <br>
                        <br>
                        <br>
                        <button onclick="location.href='../sponsors/sponsors_resistration.php'" class="btn btn-primary my-10 mx-10" style="background-color: #6bb6bb;">主催者様新規登録はこちらから</button>
                    </div>
                    <br>
                    <br>
                    <div class="text-center mb-3">
                        <button onclick="location.href='../top/top_2.php'" class="my-5 mx-5">ログインせずに見る</button>
                    </div>
                </div>
                <!-- /ボディ -->
                <!-- フッター -->
                <div class="card-footer text-right">
                    <p class="lead">&copy;A.Kubota</p>
                </div>
                <!-- /フッター -->
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>