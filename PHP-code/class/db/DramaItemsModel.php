<?php

/**
 * 作業項目モデルクラス
 */
class DramaItemsModel extends BaseModel
{
    /** コンストラクタ */
    public function __construct()
    {
        //親クラスのコンストラクタを呼び出し
        parent::__construct();
    }

    /**
     * 公演を全件取得
     *
     * @return array
     */
    public function getDramaItemAll()
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'd.id,';
        $sql .= 'd.sponsor_id,';
        $sql .= 'sp.company_name,';
        $sql .= 'd.play_day,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.ticket_price,';
        $sql .= 'd.storylines,';
        $sql .= 'd.message, ';
        $sql .= 'd.playhouse_info,';
        $sql .= 'd.seat_number ';
        $sql .= 'from dramas d ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= 'order by d.play_day desc';   // 期限日の新しい順番に並べる

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 公演を検索条件で抽出して取得
     *
     * @param string $search
     * @return array
     */
    public function getDramaItemBySearch($search)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'd.id,';
        $sql .= 'd.sponsor_id,';
        $sql .= 'sp.company_name,';
        $sql .= 'd.play_day,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.ticket_price,';
        $sql .= 'd.storylines,';
        $sql .= 'd.message,';
        $sql .= 'd.playhouse_info,';
        $sql .= 'd.seat_number ';
        $sql .= 'from dramas d ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= "and (";
        $sql .= "sp.company_name like :company_name ";
        $sql .= "or d.play_day=:play_day ";
        $sql .= "or d.playhouse_name like :playhouse_name ";
        $sql .= "or d.drama_name like :drama_name ";
        $sql .= 'or d.playhouse_info like :playhouse_info';
        $sql .= ") ";
        $sql .= 'order by d.play_day desc';

        // bindParam()の第2引数には値を直接入れることができないので
        // 下記のようにして、検索ワードを変数に入れる。
        $likeWord = "%$search%";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':company_name', $likeWord, PDO::PARAM_STR);
        $stmt->bindParam(':play_day', $search, PDO::PARAM_STR);
        $stmt->bindParam(':playhouse_name', $likeWord, PDO::PARAM_STR);
        $stmt->bindParam(':drama_name', $likeWord, PDO::PARAM_STR);
        $stmt->bindParam(':playhouse_info', $likeWord, PDO::PARAM_STR);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * レコードを全件取得する
     *
     * @return array
     */
    public function selectAll()
    {
        $sql = 'select * from dramas order by id';
        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 公演を1件登録
     *
     * @param string $data
     * @return void
     */
    public function registerDramaItem($data)
    {
        $sql = '';
        $sql .= 'insert into dramas (';
        $sql .= 'sponsor_id,';
        $sql .= 'play_day,';
        $sql .= 'playhouse_name,';
        $sql .= 'image,';
        $sql .= 'drama_name,';
        $sql .= 'ticket_price,';
        $sql .= 'storylines,';
        $sql .= 'message,';
        $sql .= 'playhouse_info,';
        $sql .= 'seat_number ';
        $sql .= ') values (';
        $sql .= ':sponsor_id,';
        $sql .= ':play_day,';
        $sql .= ':playhouse_name,';
        $sql .= ':image,';
        $sql .= ':drama_name,';
        $sql .= ':ticket_price,';
        $sql .= ':storylines,';
        $sql .= ':message,';
        $sql .= ':playhouse_info,';
        $sql .= ':seat_number ';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':sponsor_id', $data['sponsor_id'], PDO::PARAM_INT);
        $stmt->bindParam(':play_day', $data['play_day'], PDO::PARAM_STR);
        $stmt->bindParam(':playhouse_name', $data['playhouse_name'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_STR);
        $stmt->bindValue(':drama_name', $data['drama_name'], PDO::PARAM_STR);
        $stmt->bindValue(':ticket_price', $data['ticket_price'], PDO::PARAM_STR);
        $stmt->bindValue(':storylines', $data['storylines'], PDO::PARAM_STR);
        $stmt->bindValue(':message', $data['message'], PDO::PARAM_STR);
        $stmt->bindValue(':playhouse_info', $data['playhouse_info'], PDO::PARAM_STR);
        $stmt->bindValue(':seat_number', $data['seat_number'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * 座席数を登録する
     * 
     * @param string $seatData
     * @return void
     */
    public function registerSeatNumber($seatData)
    {
        $sql = '';
        $sql .= 'insert into playhouses (';
        $sql .= 'drama_id,';
        $sql .= 'playhouse_name,';
        $sql .= 'residue_seat_number ';
        $sql .= ') values (';
        $sql .= ':drama_id,';
        $sql .= ':playhouse_name,';
        $sql .= ':residue_seat_number ';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':drama_id', $seatData['drama_id'], PDO::PARAM_INT);
        $stmt->bindValue(':playhouse_name', $seatData['playhouse_name'], PDO::PARAM_STR);
        $stmt->bindValue(':residue_seat_number', $seatData['residue_seat_number'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * 公演を1件取得
     *
     * @param integer $id
     * @return array
     */
    public function getDramaItemById($id)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'd.id,';
        $sql .= 'd.sponsor_id,';
        $sql .= 'sp.company_name,';
        $sql .= 'd.play_day,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.ticket_price,';
        $sql .= 'd.storylines,';
        $sql .= 'd.message,';
        $sql .= 'd.playhouse_info,';
        $sql .= 'd.seat_number ';
        $sql .= 'from dramas d ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= 'where d.id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 登録済み公演を取得
     * 
     * @param integer $sponsorId
     * @return array
     */
    public function getDramaItemHistoryById($sponsorId)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'd.id,';
        $sql .= 'd.sponsor_id,';
        $sql .= 'sp.company_name,';
        $sql .= 'd.play_day,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.ticket_price,';
        $sql .= 'd.storylines,';
        $sql .= 'd.message,';
        $sql .= 'd.playhouse_info,';
        $sql .= 'd.seat_number ';
        $sql .= 'from dramas d ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= 'where d.sponsor_id=:id ';
        $sql .= 'order by d.play_day desc';   // 期限日の新しい順番に並べる

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $sponsorId, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }
    /**
     * 座席数を変更する(注文されたらチケットの枚数分減らす)
     * 
     * @param string $numData
     * @return array
     */
    public function updateSeatNum($numData)
    {
        $sql = '';
        $sql .= 'update playhouses set ';
        $sql .= 'drama_id=:drama_id,';
        $sql .= 'playhouse_name=:playhouse_name,';
        $sql .= 'residue_seat_number=:residue_seat_number ';
        $sql .= 'where drama_id=:drama_id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':drama_id', $numData['drama_id'], PDO::PARAM_INT);
        $stmt->bindParam(':playhouse_name', $numData['playhouse_name'], PDO::PARAM_STR);
        $stmt->bindParam(':residue_seat_number', $numData['residue_seat_number'], PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 席残数を取得する
     * 
     * @param int $id
     * @return array
     */
    public function getSeatNum($id)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'ph.residue_seat_number ';
        $sql .= 'from playhouses ph ';
        $sql .= 'inner join dramas d on ph.drama_id=d.id ';
        $sql .= 'where d.id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * チケット枚数
     * 
     * @param integer $count
     * @return array
     * 
     */
    function ticketCount($count)
    {
        $count = array(
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '10' => 10
        );

        return $count;
    }

    /**
     * 最後にインサートされたAuto Incrementの値を取得します。
     *
     * @return integer
     */
    public function lastInsertId(): int
    {
        return $this->dbh->lastInsertId();
    }
}
