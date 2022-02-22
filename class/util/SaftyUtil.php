<?php

class SaftyUtil
{
    /**
     * POSTされたデータをサニタイズ
     *
     * @param array $before サニタイズ前のPOST配列
     * @return array サニタイズ後のPOST配列
     */
    public static function sanitaize($before)
    {
        $after = array();
        foreach ($before as $k => $v) {
            $after[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        }
        return $after;
    }

    /**
     * ワンタイムトークンを発生させる
     *
     * @param string $tokenName セッションに保存するトークンのキーの名前
     * @return string
     */
    public static function generateToken($tokenName = 'token'): string
    {
        // ワンタイムトークンを生成してセッションに保存する
        $token = bin2hex(openssl_random_pseudo_bytes(Config::RANDOM_PSEUDO_STRING_LENGTH));
        $_SESSION[$tokenName] = $token;
        return $token;
    }


    /**
     * フォームで送信されてきたトークンが正しいかどうか確認(CSRF対策)
     *
     * @param string $token $token 送信されてきたトークン
     * @param string $tokenName セッションに保存されているトークンのキーの名前
     * @return boolean
     */
    public static function isValidToken($token, $tokenName = 'token'): bool
    {
        if(!isset($_SESSION[$tokenName]) || $_SESSION[$tokenName] !== $token){
            return false;
        }
        return true;
    }
    
}
