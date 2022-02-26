<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

if (empty($_SESSION['userinfo'])) {
    //未ログインのとき
    header('Location: ../customers/customers_login.php');
} else {
    $userinfo = $_SESSION['userinfo'];
}

try {
    // Customersテーブルクラスのインスタンスを生成する
    $Customersdb = new CustomersModel();

    // レコードを取得する
    $Customer = $Customersdb->getCustomerById($userinfo['id']);

    $prefecture_name = $Customersdb->getPrefectureList($_POST);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>お客様情報修正ページ</title>
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
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">お客様情報修正ページ</div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3"></div>
                </div>
                <!-- エラーメッセージ -->
                <?php if (isset($_SESSION['msg']['err'])) : ?>
                    <div class="row my-2">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                            <?php $_SESSION['msg']['err'] ?><button class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                <?php endif ?>
                <!-- エラーメッセージ ここまで -->
                <!-- 入力フォーム -->
                <div class="row my-2">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <form action="./customers_edit_acount_process.php" method="post">
                            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                            <input type="hidden" name="id" value="<?php $Customer['id'] ?>">
                            <div class="form-group">
                                <label for="family_name">姓</label>
                                <input type="text" name="family_name" id="family_name" class="form-control" value="<?= $Customer['family_name'] ?>">
                                <?php if (isset($_SESSION['family_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['family_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="first_name">名</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $Customer['first_name'] ?>">
                                <?php if (isset($_SESSION['first_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['first_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">郵便番号</label>
                                <input type="text" name="postal_code" id="postal_code" class="form-control" value="<?= $Customer['postal_code'] ?>">
                                <?php if (isset($_SESSION['postal_code_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['postal_code_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if (isset($_SESSION['postal_code_err2'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['postal_code_err2'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="prefecture_name">県名</label>
                                <select name="prefecture_name" id="prefecture_name" class="form-control">
                                    <option value="">--選択してください--</option>
                                    <?php foreach ($prefecture_name as $value) : ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value ?></option>
                                    <?php endforeach ?>
                                </select>
                                <?php if (isset($_SESSION['prefecture_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['prefecture_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="city_name">市・区名</label>
                                <input type="text" name="city_name" id="city_name" class="form-control" value="<?= $Customer['city_name'] ?>">
                                <?php if (isset($_SESSION['city_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['city_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="town_name">町・村名</label>
                                <input type="text" name="town_name" id="town_name" class="form-control" value="<?= $Customer['town_name'] ?>">
                                <?php if (isset($_SESSION['town_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['town_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="building_name">建物名・番号等（ない場合は「なし」とご記入ください）</label>
                                <input type="text" name="building_name" id="building_name" class="form-control" value="<?= $Customer['building_name'] ?>">
                                <?php if (isset($_SESSION['building_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['building_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">電話番号(ハイフンなし)</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?= $Customer['phone_number'] ?>">
                                <?php if (isset($_SESSION['phone_number_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['phone_number_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php if (isset($_SESSION['phone_number_err2'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['phone_number_err2'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="email">メールアドレス</label>
                                <input type="text" name="email" id="email" class="form-control" value="<?= $Customer['email'] ?>">
                                <?php if (isset($_SESSION['email_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['email_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="password">パスワード</label>
                                <input type="password" name="password" id="password" class="form-control" value="">
                                <?php if (isset($_SESSION['password_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['password_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="my-2 text-right">
                                <button type="submit" class="btn btn-warning" onclick="location.href='./index.php';">修正</button>
                                <button type="button" class="btn btn-primary" onclick="location.href='./index.php';">キャンセル</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-3"></div>
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