<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/DramaItemsModel.php');
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
    header('Location: ./customers_edit_acount.php');
    exit;
}

// POSTされてきた値をセッション変数に保存する
$_SESSION['post']['family_name'] = $post['family_name'];
$_SESSION['post']['first_name'] = $post['first_name'];
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
    $checkFamilyNameFlag = true;
    $checkFirstNameFlag = true;
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
    if (!ValidationUtil::isValidFamilyName($post['family_name'])) {
        $_SESSION['family_name_err'] = '姓は' . Config::MSG_WORD_COUNT2;
        $checkFamilyNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['family_name_err']);
    }
    if (!ValidationUtil::isValidFirstName($post['first_name'])) {
        $_SESSION['first_name_err'] = '名は' .  Config::MSG_WORD_COUNT2;
        $checkFirstNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['first_name_err']);
    }
    if (!ValidationUtil::isValidPostalCode($post['postal_code'])) {
        $_SESSION['postal_code_err'] = '郵便番号は' . Config::MSG_WORD_COUNT6;
        $checkPostalCodeFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['postal_code_err']);
    }
    if (!ValidationUtil::isValidPrefectureName($post['prefecture_name'])) {
        $_SESSION['prefecture_name_err'] = '県を選択してください';
        $checkPrefectureNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['prefecture_name_err']);
    }
    if (!ValidationUtil::isValidCityName($post['city_name'])) {
        $_SESSION['city_name_err'] = '市・区名は' . Config::MSG_WORD_COUNT2;
        $checkCityNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['city_name_err']);
    }
    if (!ValidationUtil::isValidTownName($post['town_name'])) {
        $_SESSION['town_name_err'] = '町・村名は' . Config::MSG_WORD_COUNT2;
        $checkTownNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['town_name_err']);
    }
    if (!ValidationUtil::isValidBuildingName($post['building_name'])) {
        $_SESSION['building_name_err'] = '建物名・番地等は' . Config::MSG_WORD_COUNT2;
        $checkBuildingNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['building_name_err']);
    }
    if (!ValidationUtil::isValidPhoneNumber($post['phone_number'])) {
        $_SESSION['phone_number_err'] = '電話番号は' . Config::MSG_WORD_COUNT4;
        $checkPhoneNumberFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['phone_number_err']);
    }
    if (!ValidationUtil::isValidEmail($post['email'])) {
        $_SESSION['email_err'] = 'Eメールは' . Config::MSG_WORD_COUNT;
        $checkEmailFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['email_err']);
    }
    if (!ValidationUtil::isValidPass($post['password'], 8)) {
        $_SESSION['password_err'] = 'パスワードは' . Config::MSG_WORD_COUNT5;
        $checkPasswordFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['password_err']);
    }
    if (preg_match("/[^0-9]/", $post['postal_code'])) {
        $_SESSION['postal_code_err2'] = "数字以外が入力されています";
        $checkPostalCodeFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['postal_code_err2']);
    }
    if (preg_match("/[^0-9]/", $post['phone_number'])) {
        $_SESSION['phone_number_err2'] = "数字以外が入力されています";
        $checkPhoneNumberFlag2 = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['phone_number_err2']);
    }


    if (
        $checkFlag
        && $checkFamilyNameFlag
        && $checkFirstNameFlag
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
        unset($_SESSION['family_name_err']);
        unset($_SESSION['first_name_err']);
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
            'family_name' => $post['family_name'],
            'first_name' => $post['first_name'],
            'postal_code' => $post['postal_code'],
            'prefecture_name' => $post['prefecture_name'],
            'city_name' => $post['city_name'],
            'town_name' => $post['town_name'],
            'building_name' => $post['building_name'],
            'phone_number' => $post['phone_number'],
            'email' => $post['email'],
            'password' => $post['password'],
        );

        // インスタンスを生成する
        $Customersdb = new CustomersModel();

        $Customersdb->updateCustomerById($data);

        unset($_SESSION['post']);

        header('Location: ./index.php');
        exit;
    } else {
        header('Location: ./customers_edit_acount.php');
        exit;
    }
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
