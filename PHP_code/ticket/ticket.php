<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

if (empty($_SESSION['userinfo'])) {
    //未ログインのとき
    header('Location: ../customers/customers_login.php');
} else {
    $userinfo = $_SESSION['userinfo'];
}

// サニタイズ
$post = SaftyUtil::sanitaize($_POST);

try {
    // インスタンスを生成する
    $dramadb = new DramaItemsModel;

    // レコードを取得する
    $drama = $dramadb->getDramaItemById($post['id']);

    $ticket_count = $dramadb->ticketCount($post);

    // インスタンスを生成する
    $customersorderdb = new CustomersModel();

    $carts = $customersorderdb->getCartItem($userinfo['id']);
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
    <title>購入画面</title>
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
                <!-- ナビバー -->
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
                <!-- /ナビバー -->
            </div>
            <!-- 検索、カートのナビバー -->
            <nav class="navbar navbar-light bg-light">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="location.href='../ticket/cart.php'">
                    カート
                    <span class="fs-p-cartItemNumber fs-client-cart-count fs-clientInfo is-ready fs-client-cart-count--0">
                        <?= count($carts); ?>
                    </span>
                </button>
            </nav>
            <!-- /検索、カートのナビバー -->
            <!-- /ヘッダー -->
            <!-- カードボディ -->
            <div class="container">
                <div class="row my-2">
                    <div class="col-sm-3 mt-3 mb-3">購入画面</div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3"></div>
                </div>
                <div class="dramainfo text-center">
                    タイトル:<?php echo $drama['drama_name'] ?>
                    <br>
                    公演期間:<?php echo $drama['play_day'] ?>
                    <br>
                    会場:<?php echo $drama['playhouse_name'] ?>
                    <br>
                    劇団名:<?php echo $drama['company_name'] ?>
                    <br>
                    チケット価格:<?php echo $drama['ticket_price'] ?>円
                    <br>
                </div>
                <!-- エラーメッセージ -->
                <?php if (isset($_SESSION['err']['msg'])) : ?>
                    <div class="row my-2">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                            <?php $_SESSION['err']['msg'] ?><button class="close" data-dismiss="alert">&times;</button>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                <?php endif ?>
                <!-- エラーメッセージ ここまで -->
                <div class="row my-2">
                    <div class="col-sm-3 mt-3"></div>
                    <div class="col-sm-6">
                        <div class="incart">
                            <form action="./ticket_process.php" method="post">
                                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                                <input type="hidden" name="id" value="<?= $userinfo['id'] ?>">
                                <input type="hidden" name="id" value="<?= $drama['id'] ?>">
                                <div class="form-group">
                                    <label for="play_day">日程選択</label>
                                    <select name="play_day" id="play_day" class="form-control">
                                        <option value="">--選択してください--</option>
                                        <!-- $drama['play_day']の配列つくる -->
                                        <?php //foreach ($drama['play_day'] as $value) : 
                                        ?>
                                        <option value="<?php echo $drama['play_day']; ?>"><?php echo $drama['play_day'] ?></option>
                                        <?php //endforeach 
                                        ?>
                                    </select>
                                    <?php if (isset($_SESSION['play_day_err'])) : ?>
                                        <div class="row my-2 ml-1">
                                            <div class="alert alert-danger alert-dismissble fade show">
                                                <?= $_SESSION['play_day_err'] ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <div class="form-group">
                                    <label for="ticket_count">枚数選択</label>
                                    <select name="ticket_count" id="ticket_count" class="form-control">
                                        <option value="">--選択してください--</option>
                                        <?php foreach ($ticket_count as $value) : ?>
                                            <option value="<?php echo $value; ?>"><?php echo $value ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <?php if (isset($_SESSION['ticket_count_err'])) : ?>
                                        <div class="row my-2 ml-1">
                                            <div class="alert alert-danger alert-dismissble fade show">
                                                <?= $_SESSION['ticket_count_err'] ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                                <div class="font-weight-bold" style="color: red;">
                                    ※カートにいれるのみで購入を確定するものではありませんのでご注意ください。
                                </div>
                                <div class="right">
                                    <input type="button" value="カートにいれる" class="btn btn-warning" onclick="submit();">
                                    <input type="button" value="キャンセル" class="btn btn-outline-primary" onclick="location.href='../top/top_2.php';">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
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
</body>

</html>