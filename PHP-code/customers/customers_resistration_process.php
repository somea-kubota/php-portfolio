<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/db/DramaItemsModel.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/SessionUtil.php');

//セッションスタート
SessionUtil::sessionStart();

unset($_SESSION['msg']['err']);
unset($_SESSION['family_name_err']);
unset($_SESSION['first_name_err']);
unset($_SESSION['postal_code_err']);
unset($_SESSION['prefecture_name_err']);
unset($_SESSION['city_name_err']);
unset($_SESSION['town_name_err']);
unset($_SESSION['building_name_err']);
unset($_SESSION['phone_number_err']);
unset($_SESSION['password_err']);
unset($_SESSION['postal_code_err2']);
unset($_SESSION['phone_number_err2']);

//ワンタイムトークンのチェック
if (!SaftyUtil::isValidToken($_POST['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['err']  = Config::MSG_INVALID_PROCESS;
    header('Location: ./customers_resistration.php');
    exit;
}

//フォームから入力した情報をセッションに保存する
$_SESSION['login'] = $_POST;

try {

    //ユーザーテーブルのインスタンスを生成
    $db = new CustomersModel();

    //レコードのインサート
    $ret = $db->findCustomerByEmail($_POST['email']);

    //ユーザーが既に登録されている場合
    if (!empty($ret)) {
        //エラーメッセージをセッションに保存して、リダイレクトする
        $_SESSION['msg']['err'] = Config::MSG_USER_DUPLICATE;
        header('Location: ./customers_resistration.php');
        exit;
    }


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
    if (!ValidationUtil::isValidFamilyName($_POST['family_name'])) {
        $_SESSION['family_name_err'] = '姓は' . Config::MSG_WORD_COUNT2;
        $checkFamilyNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['family_name_err']);
    }
    if (!ValidationUtil::isValidFirstName($_POST['first_name'])) {
        $_SESSION['first_name_err'] = '名は' .  Config::MSG_WORD_COUNT2;
        $checkFirstNameFlag = false;
    } else {
        $checkFlag = true;
        unset($_SESSION['first_name_err']);
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
        $db->insert(
            $_POST['email'],
            $_POST['password'],
            $_POST['postal_code'],
            $_POST['prefecture_name'],
            $_POST['city_name'],
            $_POST['town_name'],
            $_POST['building_name'],
            $_POST['phone_number'],
            $_POST['family_name'],
            $_POST['first_name']
        );

        //インサートが正常に処理された場合、エラーメッセージとログイン情報を削除してリダイレクト
        unset($_SESSION['login']);
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
        header('Location: ./customers_login.php');
        exit;
    } else {
        header('Location: ./customers_resistration.php');
        exit;
    }
} catch (Exception $e) {
    // エラーメッセージをセッションに保存してエラーページにリダイレクト
    $_SESSION['msg']['err'] = Config::MSG_EXCEPTION;
    header('Location: ../error/error.php');
    exit;
}
