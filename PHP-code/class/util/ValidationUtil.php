<?php

/**
 * バリデーションユーティリティクラス
 */
class ValidationUtil
{

    /** 正しい日付形式の文字列かどうかを判定 */
    public static function isDate($date)
    {
        // strtotime()関数を使って、タイムスタンプに変換できるかどうかで正しい日付かどうかを調べる
        // https://www.php.net/manual/ja/function.strtotime.php
        // 参照
        return strtotime($date) == false ? false : true;
    }

    /** 項目名の長さ（文字数）が正しいかどうかを判定 */
    public static function isValidFamilyName($FamilyName)
    {
        if (strlen($FamilyName) > 20) {
            return false;
        } elseif (strlen($FamilyName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidFirstName($FirstName)
    {
        if (strlen($FirstName) > 20) {
            return false;
        } elseif (strlen($FirstName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPostalCode($PostalCode)
    {
        if (strlen($PostalCode) > 7) {
            return false;
        } elseif (strlen($PostalCode) == 0) {
            return false;
        } elseif (strlen($PostalCode) == 7) {
            return true;
        } else {
            return false;
        }
    }
    public static function isValidPrefectureName($PrefectureName)
    {
        if (strlen($PrefectureName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidCityName($CityName)
    {
        if (strlen($CityName) > 40) {
            return false;
        } elseif (strlen($CityName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidTownName($TownName)
    {
        if (strlen($TownName) > 40) {
            return false;
        } elseif (strlen($TownName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidBuildingName($BuildingName)
    {
        if (strlen($BuildingName) > 40) {
            return false;
        } elseif (strlen($BuildingName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPhoneNumber($PhoneNumber)
    {
        if (strlen($PhoneNumber) > 12) {
            return false;
        } elseif (strlen($PhoneNumber) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidEmail($Email)
    {
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidCompanyName($CompanyName)
    {
        if (strlen($CompanyName) > 40) {
            return false;
        } elseif (strlen($CompanyName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPlayhouseName($PlayhouseName)
    {
        if (strlen($PlayhouseName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPlayHouseInfo($PlayHouseInfo)
    {
        if (strlen($PlayHouseInfo) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidDramaName($DramaName)
    {
        if (strlen($DramaName) > 40) {
            return false;
        } elseif (strlen($DramaName) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidTicketPrice($TicketPrice)
    {
        if (strlen($TicketPrice) > 20) {
            return false;
        } elseif (!is_numeric($TicketPrice)) {
            return false;
        } elseif (strlen($TicketPrice) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidSeatNumber($SeatNumber)
    {
        if (!is_numeric($SeatNumber)) {
            return false;
        } elseif (strlen($SeatNumber) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPass(string $str, int $length)
    {
        if (mb_strlen($str) < $length) {
            return  false;
        } elseif (mb_strlen($str) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidDramaImage($DramaImage)
    {
        if ([$DramaImage] <= 0){
            return false;
        } else {
            return true;
        }
    }
    public static function isValidPlayDay($PlayDay)
    {
        if (strlen($PlayDay) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidStorylines($Storylines)
    {
        if (strlen($Storylines) == 0) {
            return false;
        } else {
            return true;
        }
    }
    public static function isValidMessage($Storylines)
    {
        if (strlen($Storylines) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function isValidTicketCount($ticketcount)
    {
        if (empty($ticketcount)) {
            return false;
        } elseif ($ticketcount < 1) {
            return false;
        } elseif ($ticketcount > 10) {
            return false;
        } else {
            return true;
        }
    }
    

    /** 指定IDのユーザーが存在するかどうか判定 */
    public static function isValidCustomersId($customersId)
    {
        // $customersIdが数字でなかったら、falseを返却
        if (!is_numeric($customersId)) {
            return false;
        }

        // $customersIdが0以下はありえないので、falseを返却
        elseif ($customersId <= 0) {
            return false;
        }

        // CustomersModelクラスの使って、該当のユーザーを検索した結果を返却
        require_once('./class/db/CustomersModel.php');
        $db = new CustomersModel();
        return $db->count($customersId);
    }

    /** 指定IDのユーザーが存在するかどうか判定 */
    public static function isValidSponsorsId($sponsorsId)
    {
        // $sponsorsIdが数字でなかったら、falseを返却
        if (!is_numeric($sponsorsId)) {
            return false;
        }

        // $sponsorsIdが0以下はありえないので、falseを返却
        elseif ($sponsorsId <= 0) {
            return false;
        }

        // SponsorsIdModelクラスの使って、該当のユーザーを検索した結果を返却
        require_once('./class/db/SponsorsModel.php');
        $db = new SponsorsModel();
        return $db->count($sponsorsId);
    }

}
