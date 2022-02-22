<?php
require_once('../class/config/Config.php');
require_once('../class/db/BaseModel.php');
require_once('../class/db/CustomersModel.php');
require_once('../class/util/SaftyUtil.php');
require_once('../class/util/ValidationUtil.php');
require_once('../class/util/SessionUtil.php');

SessionUtil::sessionStart();

$post = SaftyUtil::sanitaize($_POST);

if (!isset($_SESSION['userinfo'])) {
    header('Location: ../customers/customers_login.php');
    exit;
} else {
    // ログイン済みのとき
    $userinfo = $_SESSION['userinfo'];
}

try {
    $db = new CustomersModel();
    $db->deleteCartItemById($post['id']);

    unset($_SESSION['post']);

    header('Location: ./cart.php');
} catch (Exception $e) {
    // var_dump($e);
    header('Location: ../error/error.php');
}
