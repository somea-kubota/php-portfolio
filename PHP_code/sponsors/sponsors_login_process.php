<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

SessionUtil::sessionStart();

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($_POST['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./sponsors_login.php');
    exit;
}

//セッションに保存されているログイン情報を削除する
unset($_SESSION['login']);

// セッションに入っていたらリセットする
if (isset($_SESSION['msg']['err'])) {
    unset($_SESSION['msg']['err']);
}

// サニタイズ
$post = SaftyUtil::sanitaize($_POST);

//ログイン情報をセッションに保存する
$_SESSION['login'] = $_POST;

//ログイン3回失敗でログインできないようにする
if (isset($_SESSION['login_failure']) && $_SESSION['login_failure'] >= 3) {
    $_SESSION['msg']['err'] = Config::MSG_USER_LOGIN_TRY_TIMES_OVER;
    header('Location: ../error/error.php');
    exit;
}

try {
    //ユーザーテーブルクラスのインスタンスを生成する
    $db = new SponsorsModel();

    //ログイン情報からユーザーを検索
    $userinfo = $db->getSponsor($post['email'], $post['password']);

    //ログイン不可の場合
    if (empty($userinfo)) {
        //ログイン失敗回数を保存する
        if (isset($_SESSION['login_failure'])) {
            $_SESSION['login_failure']++;
        } else {
            $_SESSION['login_failure'] = 1;
        }

        // POSTされてきたemailをセッションに保存→ログインページのメールアドレスのテキストボックスに表示させる
        $_SESSION['post']['email'] = $post['email'];

        //エラーメッセージをセッションに保存してcustomers_login.phpにリダイレクト
        $_SESSION['msg']['err'] = Config::MSG_USER_LOGIN_FAILURE;
        header('Location: ./sponsors_login.php');
        exit;
    } else {
        //ログイン成功のときは、ログイン失敗回数を削除する
        unset($_SESSION['login_failure']);

        //ユーザー情報をセッションに保存
        $_SESSION['userinfo'] = $userinfo;

        // セッション変数に保存されているPOSTされてきたデータを削除
        if (isset($_SESSION['post'])) {
            unset($_SESSION['post']);
        }

        // セッションに保存されているエラーメッセージを削除
        if (isset($_SESSION['msg']['err'])) {
            unset($_SESSION['msg']['err']);
        }
        // index.phpにリダイレクト
        header('Location: ./index.php');
    }
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
