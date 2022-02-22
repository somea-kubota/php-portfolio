<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/SponsorsModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');
require_once('../class/util/ValidationUtil.php');

SessionUtil::sessionStart();

//サニタイズ
$post = SaftyUtil::sanitaize($_POST);

// ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./sponsors_edit_acount.php');
    exit;
}

// POSTされてきた値をセッション変数に保存する
$_SESSION['post']['company_name'] = $post['company_name'];
$_SESSION['post']['postal_code'] = $post['postal_code'];
$_SESSION['post']['prefecture_name'] = $post['prefecture_name'];
$_SESSION['post']['city_name'] = $post['city_name'];
$_SESSION['post']['town_name'] = $post['town_name'];
$_SESSION['post']['building_name'] = $post['building_name'];
$_SESSION['post']['phone_number'] = $post['phone_number'];
$_SESSION['post']['email'] = $post['email'];
$_SESSION['post']['password'] = $post['password'];

try {
    $checkFlag = true;
    $checkCompanyNameFlag = true;
    $checkPostalCodeFlag = true;
    $checkPrefectureNameFlag = true;
    $checkCityNameFlag = true;
    $checkTownNameFlag = true;
    $checkBuildingNameFlag = true;
    $checkPhoneNumberFlag = true;
    $checkEmailFlag = true;
    $checkPasswordFlag = true;
    $checkPostalCodeFlag2 = true;
    $checkPhoneNumberFlag2 = true;


    // 項目名バリデーション
    if (!ValidationUtil::isValidCompanyName($_POST['company_name'])) {
        $_SESSION['company_name_err'] = '団体名は' . Config::MSG_WORD_COUNT2;
        $checkCompanyNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['company_name_err']);
    }
    if (!ValidationUtil::isValidPostalCode($_POST['postal_code'])) {
        $_SESSION['postal_code_err'] = '郵便番号は' . Config::MSG_WORD_COUNT6;
        $checkPostalCodeFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['postal_code_err']);
    }
    if (!ValidationUtil::isValidPrefectureName($_POST['prefecture_name'])) {
        $_SESSION['prefecture_name_err'] = '県を選択してください';
        $checkPrefectureNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['prefecture_name_err']);
    }
    if (!ValidationUtil::isValidCityName($_POST['city_name'])) {
        $_SESSION['city_name_err'] = '市・区名は' . Config::MSG_WORD_COUNT2;
        $checkCityNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['city_name_err']);
    }
    if (!ValidationUtil::isValidTownName($_POST['town_name'])) {
        $_SESSION['town_name_err'] = '町・村名は' . Config::MSG_WORD_COUNT2;
        $checkTownNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['town_name_err']);
    }
    if (!ValidationUtil::isValidBuildingName($_POST['building_name'])) {
        $_SESSION['building_name_err'] = '建物名・番地等は' . Config::MSG_WORD_COUNT2;
        $checkBuildingNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['building_name_err']);
    }
    if (!ValidationUtil::isValidPhoneNumber($_POST['phone_number'])) {
        $_SESSION['phone_number_err'] = '電話番号は' . Config::MSG_WORD_COUNT4;
        $checkPhoneNumberFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['phone_number_err']);
    }
    if (!ValidationUtil::isValidEmail($_POST['email'])) {
        $_SESSION['email_err'] = 'Eメールは' . Config::MSG_WORD_COUNT;
        $checkEmailFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['email_err']);
    }
    if (!ValidationUtil::isValidPass($_POST['password'], 8)) {
        $_SESSION['password_err'] = 'パスワードは' . Config::MSG_WORD_COUNT5;
        $checkPasswordFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['password_err']);
    }
    if (preg_match("/[^0-9]/", $_POST['postal_code'])) {
        $_SESSION['postal_code_err2'] = "数字以外が入力されています";
        $checkPostalCodeFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['postal_code_err2']);
    }
    if (preg_match("/[^0-9]/", $_POST['phone_number'])) {
        $_SESSION['phone_number_err2'] = "数字以外が入力されています";
        $checkPhoneNumberFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['phone_number_err2']);
    }

    if (
        $checkFlag
        && $checkCompanyNameFlag
        && $checkPostalCodeFlag
        && $checkPrefectureNameFlag
        && $checkCityNameFlag
        && $checkTownNameFlag
        && $checkBuildingNameFlag
        && $checkPhoneNumberFlag
        && $checkEmailFlag
        && $checkPasswordFlag
        && $checkPostalCodeFlag2
        && $checkPhoneNumberFlag2
    ) {

        unset($_SESSION['err']['msg']);
        unset($_SESSION['company_name_err']);
        unset($_SESSION['postal_code_err']);
        unset($_SESSION['prefecture_name_err']);
        unset($_SESSION['city_name_err']);
        unset($_SESSION['town_name_err']);
        unset($_SESSION['building_name_err']);
        unset($_SESSION['phone_number_err']);
        unset($_SESSION['email_err']);
        unset($_SESSION['password_err']);
        unset($_SESSION['postal_code_err2']);
        unset($_SESSION['phone_number_err2']);


        // データベースに登録する内容を連想配列にする。
        $data = array(
            'id' => $_SESSION['userinfo']['id'],
            'company_name' => $post['company_name'],
            'email' => $post['email'],
            'password' => $post['password'],
            'postal_code' => $post['postal_code'],
            'prefecture_name' => $post['prefecture_name'],
            'city_name' => $post['city_name'],
            'town_name' => $post['town_name'],
            'building_name' => $post['building_name'],
            'phone_number' => $post['phone_number'],
            'company_name' => $post['company_name']
        );

        // インスタンスを生成する
        $Sponsorsdb = new SponsorsModel();

        $Sponsorsdb->updateSponsorById($data);

        unset($_SESSION['post']);

        header('Location: ./index.php');
        exit;
    } else {
        header('Location: ./sponsors_edit_acount.php');
        exit;
    }
} catch (Exception $e) {
    // // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
