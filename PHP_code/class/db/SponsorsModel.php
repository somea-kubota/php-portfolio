<?php

class SponsorsModel extends BaseModel
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * メールアドレスとパスワードが一致するユーザーを取得する
     *
     * @param string $email
     * @param string $password
     * @return array ユーザーの連想配列（一致するユーザーがいなかったときは、空の配列を返す）
     */
    public function getSponsor($email, $password): array
    {
        $rec = $this->findSponsorByEmail($email);

        if (empty($rec)) {
            return [];
        } elseif (password_verify($password, $rec['password'])) {
            return $rec;
        } else {
            return [];
        }
    }

    /**
     * 同一ののユーザーを探す
     *
     * @param string $email
     * @return array ユーザーの連想配列
     */
    public function findSponsorByEmail($email): array
    {
        $sql = 'select * from sponsors where is_deleted=0 and email=:email';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($rec)) {
            return [];
        } else {
            return $rec;
        }
    }

    /**
     * レコードを全件取得する
     *
     * @return array
     */
    public function selectAll()
    {
        $sql = 'select * from sponsors order by id';
        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * sponsors_edit_acount.phpに表示されるレコードを１件取得する
     *
     * @param integer $id
     * @return array
     */
    public function getSponsorById($id)
    {
        $sql = 'select * from sponsors where id=:id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * DBへアクセスしてそのIDのユーザが存在するかのチェックを行う
     *
     * @return array
     */
    public function count($id)
    {
        // cにレコード数が入る
        $sql = 'select count(*) as c from sponsors where id=:id';
        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        return $count['c'];
    }


    /**
     * 会員登録
     *
     * @param string $email
     * @param string $password
     * @param string $postal_code
     * @param string $prefecture_name
     * @param string $city_name
     * @param string $town_name
     * @param string $building_name
     * @param string $phone_number
     * @param string $company_name
     * @return void
     */
    public function insert(
        $email,
        $password,
        $postal_code,
        $prefecture_name,
        $city_name,
        $town_name,
        $building_name,
        $phone_number,
        $company_name
    ): void {
        $hashpass = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'insert into sponsors (';
        $sql .= 'email,';
        $sql .= 'password,';
        $sql .= 'postal_code,';
        $sql .= 'prefecture_name,';
        $sql .= 'city_name,';
        $sql .= 'town_name,';
        $sql .= 'building_name,';
        $sql .= 'phone_number,';
        $sql .= 'company_name';
        $sql .= ') values (';
        $sql .= ':email,';
        $sql .= ':password,';
        $sql .= ':postal_code,';
        $sql .= ':prefecture_name,';
        $sql .= ':city_name,';
        $sql .= ':town_name,';
        $sql .= ':building_name,';
        $sql .= ':phone_number,';
        $sql .= ':company_name';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashpass, PDO::PARAM_STR);
        $stmt->bindValue(':postal_code', $postal_code, PDO::PARAM_STR);
        $stmt->bindValue(':prefecture_name', $prefecture_name, PDO::PARAM_STR);
        $stmt->bindValue(':city_name', $city_name, PDO::PARAM_STR);
        $stmt->bindValue(':town_name', $town_name, PDO::PARAM_STR);
        $stmt->bindValue(':building_name', $building_name, PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $company_name, PDO::PARAM_STR);
        $stmt->execute();
    }


    /**
     * 会員情報を更新する
     *
     *@param array $data 更新する作業項目の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALS
     */
    public function updateSponsorById($data)
    {
        // $data['id']が存在しなかったら、falseを返却
        if (!isset($data['id'])) {
            return false;
        }

        // $data['id']が数字でなかったら、falseを返却する。
        if (!is_numeric($data['id'])) {
            return false;
        }

        // $data['id']が0以下はありえないので、falseを返却
        if ($data['id'] <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update sponsors set ';
        $sql .= 'email=:email,';
        $sql .= 'password=:password,';
        $sql .= 'postal_code=:postal_code,';
        $sql .= 'prefecture_name=:prefecture_name,';
        $sql .= 'city_name=:city_name,';
        $sql .= 'town_name=:town_name,';
        $sql .= 'building_name=:building_name,';
        $sql .= 'phone_number=:phone_number,';
        $sql .= 'company_name=:company_name ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);


        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':postal_code', $data['postal_code'], PDO::PARAM_STR);
        $stmt->bindValue(':prefecture_name', $data['prefecture_name'], PDO::PARAM_STR);
        $stmt->bindValue(':city_name', $data['city_name'], PDO::PARAM_STR);
        $stmt->bindValue(':town_name', $data['town_name'], PDO::PARAM_STR);
        $stmt->bindValue(':building_name', $data['building_name'], PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $data['phone_number'], PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $data['company_name'], PDO::PARAM_STR);
        $ret = $stmt->execute();

        return $ret;
    }


    /**
     * 退会(過去公演情報を残すため、論理削除する)
     *
     * @param int $id
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function delete($id)
    {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update sponsors set ';
        $sql .= 'is_deleted=1 ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 登録済み公演を取得する
     * 
     * @param integer $id
     * @return array
     */
    public function getOrderHistory($id)
    {
        $sql = '';
        $sql .= 'select ';
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
        $sql .= 'where sp.id=:id ';
        $sql .= 'order by o.order_date desc';   // 期限日の新しい順番に並べる

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * 都道府県一覧リスト取得
     * 
     * @param string $pref
     * @return array
     */
    function getPrefectureList($pref)
    {
        $pref = array(
            '北海道',
            '青森県',
            '岩手県',
            '宮城県',
            '秋田県',
            '山形県',
            '福島県',
            '茨城県',
            '栃木県',
            '群馬県',
            '埼玉県',
            '千葉県',
            '東京都',
            '神奈川県',
            '新潟県',
            '富山県',
            '石川県',
            '福井県',
            '山梨県',
            '長野県',
            '岐阜県',
            '静岡県',
            '愛知県',
            '三重県',
            '滋賀県',
            '京都府',
            '大阪府',
            '兵庫県',
            '奈良県',
            '和歌山県',
            '鳥取県',
            '島根県',
            '岡山県',
            '広島県',
            '山口県',
            '徳島県',
            '香川県',
            '愛媛県',
            '高知県',
            '福岡県',
            '佐賀県',
            '長崎県',
            '熊本県',
            '大分県',
            '宮崎県',
            '鹿児島県',
            '沖縄県'
        );

        return $pref;
    }
}
