<?php
require_once('../class/config/Config.php');

/**
 * 基本モデルクラス
 */
class BaseModel
{
    /** @var object PDOインスタンス */
    protected $dbh;

    /**
     * コンストラクタ
     * データベースへの接続が失敗した場合、PDOExceptionがthrowされる
     */
    public function __construct()
    {
        $dsn = 'mysql:dbname=' . Config::DB_NAME . ';host='.Config::DB_HOST.';charset=utf8';
        // self::DB_NAME
        $this->dbh = new PDO($dsn,Config::DB_USER, Config::DB_PASS);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    /** トランザクションを開始 */
    public function begin()
    {
        $this->dbh->beginTransaction();
    }


    /** トランザクションをコミット */
    public function commit() 
    {
        $this->dbh->commit();
    }


    /** トランザクションをロールバック */
    public function rollback()
    {
        $this->dbh->rollback();
    }
    
}
