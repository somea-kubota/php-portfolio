<?php
 /**
  * 設定クラス
  */
class Config
{
    
    /** DB関連 */

    /**
     *  @var string 接続DB名
     */
    const DB_NAME = 'order_tickets';

    /**
     *  @var string DBホスト名
     */
    const DB_HOST = 'localhost';

    /**
     *  @var string 接続ユーザー名
     */
    const DB_USER = 'root';

    /**
     *  @var string 接続パスワード
     */
    const DB_PASS = '';


    /** ワンタイムトークン */

    //openssl_random_pseudo_bytes()で使用する文字列の長さ
    const RANDOM_PSEUDO_STRING_LENGTH = 32;
    

    /** メッセージ関連 */

    /**
     * @var string ワンタイムトークンが一致しないとき
     */
    const MSG_INVALID_PROCESS = '不正な処理が行われました。';
    
    const MSG_USER_LOGIN_FAILURE = 'メールアドレスまたはパスワードが違います。';

    const MSG_EXCEPTION = '申し訳ございません。エラーが発生しました。';

    const MSG_WORD_COUNT = '項目名を正しく入力してください。';

    const MSG_WORD_COUNT2 = '恐れ入りますが、1文字以上20文字以内で入力してください。';

    const MSG_WORD_COUNT3 = '恐れ入りますが、1文字以上10文字以内で入力してください。';

    const MSG_WORD_COUNT4 = '恐れ入りますが、1文字以上12文字以内で入力してください。';
    
    const MSG_WORD_COUNT5 = '恐れ入りますが、8文字以上で入力してください。';
    
    const MSG_WORD_COUNT6 = '恐れ入りますが、7文字で入力してください。';

    const MSG_WORD_COUNT7 = '恐れ入りますが、1文字以上入力してください。';

    const MSG_COUNT = '恐れ入りますが、チケットは1枚から10枚までのみ選択可能です。';

    const MSG_ESSENTIAL = '必須項目が入力されていません。';
    
    /** @var string ログイン試行回数オーバー */
    const MSG_USER_LOGIN_TRY_TIMES_OVER = 'ログインできません。トップページへ戻ってやり直してください。';

    //ユーザーが重複しているときのメッセージ
    const MSG_USER_DUPLICATE = '既に同じユーザーが登録されています。';

}
