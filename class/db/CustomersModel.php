<?php

class CustomersModel extends BaseModel
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * すべてのユーザーの情報を取得します。
     *
     * @return array ユーザーのレコードの配列
     */
    public function getCustomersAll()
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'id,';
        $sql .= 'email,';
        $sql .= 'password,';
        $sql .= 'postal_code,';
        $sql .= 'prefecture_name,';
        $sql .= 'city_name,';
        $sql .= 'town_name,';
        $sql .= 'building_name,';
        $sql .= 'phone_number,';
        $sql .= 'family_name,';
        $sql .= 'first_name ';
        $sql .= 'from customers ';
        $sql .= 'order by id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * メールアドレスとパスワードが一致するユーザーを取得する
     *
     * @param string $email
     * @param string $password
     * @return array ユーザーの連想配列（一致しないユーザーがなかったときは、空の配列
     */
    public function getCustomer($email, $password): array
    {
        $rec = $this->findCustomerByEmail($email);

        if (empty($rec)) {
            return [];
        }

        if (password_verify($password, $rec['password'])) {

            return $rec;
        }
        return [];
    }

    /**
     * 同一ののユーザーを探す
     *
     * @param string $email
     * @return array ユーザーの連想配列
     */
    public function findCustomerByEmail($email): array
    {
        $sql = 'select * from customers where email=:email';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($rec)) {
            return [];
        }
        return $rec;
    }

    /**
     * レコードを全件取得する
     *
     * @return array
     */
    public function selectAll()
    {
        $sql = 'select * from customers order by id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * customers_edit_acount.phpに表示されるレコードを１件取得する
     *
     * @param integer $id
     * @return array
     */
    public function getCustomerById($id)
    {
        $sql = 'select * from customers where id=:id';

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
        $sql = 'select count(*) as c from customers where id=:id';
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
     * @param string $family_name
     * @param string $first_name
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
        $family_name,
        $first_name
    ): void {
        $hashpass = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'insert into customers (';
        $sql .= 'email,';
        $sql .= 'password,';
        $sql .= 'postal_code,';
        $sql .= 'prefecture_name,';
        $sql .= 'city_name,';
        $sql .= 'town_name,';
        $sql .= 'building_name,';
        $sql .= 'phone_number,';
        $sql .= 'family_name,';
        $sql .= 'first_name';
        $sql .= ') values (';
        $sql .= ':email,';
        $sql .= ':password,';
        $sql .= ':postal_code,';
        $sql .= ':prefecture_name,';
        $sql .= ':city_name,';
        $sql .= ':town_name,';
        $sql .= ':building_name,';
        $sql .= ':phone_number,';
        $sql .= ':family_name,';
        $sql .= ':first_name';
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
        $stmt->bindValue(':family_name', $family_name, PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);

        $stmt->execute();
    }


    /**
     * 会員情報を更新する
     * 
     *@param array $data 更新する作業項目の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function updateCustomerById($data)
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
        $sql .= 'update customers set ';
        $sql .= 'email=:email,';
        $sql .= 'password=:password,';
        $sql .= 'postal_code=:postal_code,';
        $sql .= 'prefecture_name=:prefecture_name,';
        $sql .= 'city_name=:city_name,';
        $sql .= 'town_name=:town_name,';
        $sql .= 'building_name=:building_name,';
        $sql .= 'phone_number=:phone_number,';
        $sql .= 'family_name=:family_name, ';
        $sql .= 'first_name=:first_name ';
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
        $stmt->bindValue(':family_name', $data['family_name'], PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $data['first_name'], PDO::PARAM_STR);
        $ret = $stmt->execute();

        return $ret;
    }


    /**
     * 退会
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $sql = 'delete from customers where id=:id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * カートに商品を追加
     *
     * @param string $cartdata
     * @return array
     */
    public function registerCartItem($cartdata)
    {
        $sql = '';
        $sql .= 'insert into carts (';
        $sql .= 'drama_id,';
        $sql .= 'customer_id,';
        $sql .= 'play_date,';
        $sql .= 'count';
        $sql .= ') values (';
        $sql .= ':drama_id,';
        $sql .= ':customer_id,';
        $sql .= ':play_date,';
        $sql .= ':count';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':drama_id', $cartdata['drama_id'], PDO::PARAM_INT);
        $stmt->bindParam(':customer_id', $cartdata['customer_id'], PDO::PARAM_INT);
        $stmt->bindValue(':play_date', $cartdata['play_date'], PDO::PARAM_STR);
        $stmt->bindValue(':count', $cartdata['count'], PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * カートの商品を取得
     *
     * @param int $customerId
     * @return array
     */
    public function getCartItem($customerId)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'ca.id,';
        $sql .= 'ca.drama_id,';
        $sql .= 'ca.customer_id,';
        $sql .= 'ca.play_date,';
        $sql .= 'ca.count,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.ticket_price,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'sp.company_name ';
        $sql .= 'from carts ca ';
        $sql .= 'inner join dramas d on ca.drama_id=d.id ';
        $sql .= 'inner join customers cu on ca.customer_id=cu.id ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= 'where ca.customer_id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * カートの商品を一件取得
     * 
     * @param int $customerId
     * @return array
     */
    public function getCartItembyid($cartId)
    {
        $sql = 'select * from carts where id=:id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $cartId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * カートのチケットの枚数を更新する
     * 
     * @param int $data 更新する作業項目の連想配列
     * @return array
     */
    public function updateCartItemById($data)
    {
        $sql = '';
        $sql .= 'update carts set ';
        $sql .= 'drama_id=:drama_id,';
        $sql .= 'customer_id=:customer_id,';
        $sql .= 'play_date=:play_date,';
        $sql .= 'count=:count ';
        $sql .= 'where id=:id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':drama_id', $data['drama_id'], PDO::PARAM_INT);
        $stmt->bindParam(':customer_id', $data['customer_id'], PDO::PARAM_INT);
        $stmt->bindParam(':play_date', $data['play_date'], PDO::PARAM_STR);
        $stmt->bindParam(':count', $data['count'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 商品を1件カートから削除する
     * 
     * @param int $id
     * @return array
     */
    public function deleteCartItemById($id)
    {
        $sql = 'delete from carts where id=:id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * 商品を購入する(ordersテーブルに情報登録)
     * 
     * @param int $orderdata
     * @return void
     */
    public function ordertickets($orderdata)
    {
        $sql = '';
        $sql .= 'insert into orders (';
        $sql .= 'drama_id,';
        $sql .= 'customer_id,';
        $sql .= 'total_price,';
        $sql .= 'order_date,';
        $sql .= 'family_name,';
        $sql .= 'first_name,';
        $sql .= 'postal_code,';
        $sql .= 'prefecture_name,';
        $sql .= 'city_name,';
        $sql .= 'town_name,';
        $sql .= 'building_name,';
        $sql .= 'phone_number ';
        $sql .= ') values (';
        $sql .= ':drama_id,';
        $sql .= ':customer_id,';
        $sql .= ':total_price,';
        $sql .= ':order_date,';
        $sql .= ':family_name,';
        $sql .= ':first_name,';
        $sql .= ':postal_code,';
        $sql .= ':prefecture_name,';
        $sql .= ':city_name,';
        $sql .= ':town_name,';
        $sql .= ':building_name,';
        $sql .= ':phone_number';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':drama_id', $orderdata['drama_id'], PDO::PARAM_INT);
        $stmt->bindParam(':customer_id', $orderdata['customer_id'], PDO::PARAM_INT);
        $stmt->bindValue(':total_price', $orderdata['total_price'], PDO::PARAM_INT);
        $stmt->bindValue(':order_date', $orderdata['order_date'], PDO::PARAM_STR);
        $stmt->bindValue(':family_name', $orderdata['family_name'], PDO::PARAM_STR);
        $stmt->bindValue(':first_name', $orderdata['first_name'], PDO::PARAM_STR);
        $stmt->bindValue(':postal_code', $orderdata['postal_code'], PDO::PARAM_STR);
        $stmt->bindValue(':prefecture_name', $orderdata['prefecture_name'], PDO::PARAM_STR);
        $stmt->bindValue(':city_name', $orderdata['city_name'], PDO::PARAM_STR);
        $stmt->bindValue(':town_name', $orderdata['town_name'], PDO::PARAM_STR);
        $stmt->bindValue(':building_name', $orderdata['building_name'], PDO::PARAM_STR);
        $stmt->bindValue(':order_date', $orderdata['order_date'], PDO::PARAM_STR);
        $stmt->bindValue(':phone_number', $orderdata['phone_number'], PDO::PARAM_STR);
        $stmt->execute();
    }


    /**
     * 購入詳細を登録する
     * 
     * @param string $data
     * @return void
     */
    public function resistrationOrders($data)
    {
        $sql = '';
        $sql .= 'insert into `order-datails` (';
        $sql .= 'order_id,';
        $sql .= 'price,';
        $sql .= 'count ';
        $sql .= ') values (';
        $sql .= ':order_id,';
        $sql .= ':price,';
        $sql .= ':count';
        $sql .= ')';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':order_id', $data['order_id'], PDO::PARAM_INT);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_INT);
        $stmt->bindValue(':count', $data['count'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * カートを空にする
     *
     * @param integer $customerId
     * @return void
     */
    public function cartDelete($customerId)
    {

        $sql = 'delete from carts where customer_id=:customer_id';

        $stmt = $this->dbh->prepare($sql);

        $stmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * 購入履歴を取得する
     * 
     * @param integer $id
     * @return array
     */
    public function getOrderHistory($id)
    {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'o.id,';
        $sql .= 'o.drama_id,';
        $sql .= 'd.image,';
        $sql .= 'd.drama_name,';
        $sql .= 'd.play_day,';
        $sql .= 'd.playhouse_name,';
        $sql .= 'd.playhouse_info,';
        $sql .= 'sp.company_name,';
        $sql .= 'od.count,';
        $sql .= 'o.total_price,';
        $sql .= 'o.order_date,';
        $sql .= 'o.customer_id ';
        $sql .= 'from orders o ';
        $sql .= 'inner join `order-datails` od on o.id=od.order_id ';
        $sql .= 'inner join dramas d on o.drama_id=d.id ';
        $sql .= 'inner join customers cu on o.customer_id=cu.id ';
        $sql .= 'inner join sponsors sp on d.sponsor_id=sp.id ';
        $sql .= 'where o.customer_id=:id ';
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
