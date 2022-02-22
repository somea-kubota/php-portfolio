<?php
require_once('../class/db/BaseModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/config/Config.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

try {
    $sponsorsdb = new SponsorsModel();

    $sponsor = $sponsorsdb->selectAll();

    $prefecture_name = $sponsorsdb->getPrefectureList($_POST);
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
    <title>主催者様新規登録</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

</head>
<style>
    body {
        background-image: url(../image/kyle-head-p6rNTdAPbuk-unsplash.jpg);
        background-repeat: no-repeat
    }
</style>

<body>
    <!-- ヘッダー -->
    <div class="col-sm-3"></div>
    <div class="col-sm-6 mx-auto">
        <div class="card mt-3 mb-3">
            <!-- ナビバー -->
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
            <!-- /ナビバー -->
            <!-- /ヘッダー -->
            <!-- コンテナ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">主催者様新規登録ページ</div>
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
                        <form action="./sponsors_resistration_process.php" method="post">
                            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">

                            <div class="form-group">
                                <label for="company_name">団体名</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                                <?php if (isset($_SESSION['company_name_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['company_name_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">郵便番号(ハイフンなし)</label>
                                <input type="text" class="form-control" name="postal_code" id="postal_code">
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
                                <select name="prefecture_name" id="prefecture_name" name="prefecture_name" class="form-control">
                                    <option value="">--選択してください--</option>
                                    <?php foreach ($prefecture_name as $value) { ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php } ?>
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
                                <input type="text" class="form-control" name="city_name" id="city_name">
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
                                <input type="text" class="form-control" name="town_name" id="town_name">
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
                                <input type="text" class="form-control" name="building_name" id="building_name">
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
                                <input type="text" class="form-control" name="phone_number" id="phone_number">
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
                                <input type="text" class="form-control" name="email" id="email">
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
                                <input type="password" class="form-control" id="password" name="password">
                                <?php if (isset($_SESSION['password_err'])) : ?>
                                    <div class="row my-2 ml-1">
                                        <div class="alert alert-danger alert-dismissble fade show">
                                            <?= $_SESSION['password_err'] ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                            <div class="text_right">
                                <input type="button" value="登録" class="btn btn-primary" onclick="submit();">
                                <input type="button" value="キャンセル" class="btn btn-outline-primary" onclick="location.href='../top/top_1.php';">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
                <!-- 入力フォーム ここまで -->
            </div>
            <!-- コンテナ ここまで -->
            <!-- カードフッター -->
            <div class="card-footer text-right mb-3">
                <p class="lead">&copy;kubota</p>
            </div>
            <!-- /カードフッター -->
        </div>
    </div>
    <div class="col-sm-3"></div>

    <!-- 必要なJavascriptを読み込む -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>