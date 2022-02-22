<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラーページ</title>
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
                <nav class="navbar" style="background-color: darkred;">
                    <a class="navbar-brand text-white" href="./top_2.php">ちけっと</a>
                </nav>
            </div>
            <!-- /ヘッダー -->
            <!-- カードボディ -->
            <div class="container">
                <div class="card-body">
                    <!-- エラーメッセージ -->
                    <div class="row my-2">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                            申し訳ございません。<br>エラーが発生しました。
                            <form class="mt-4">
                                <input type="button" class="btn btn-danger" value="topへ戻る" onclick="location.href='../top/top_1.php'">
                            </form>

                        </div>
                        <div class="col-sm-3"></div>
                    </div>
                    <!-- エラーメッセージ ここまで -->
                </div>
            </div>
            <!-- /カードボディ -->
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