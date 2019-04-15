<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： データベース用サポート処理ライブラリー
    ■概　要： 登録、更新、削除などのDB登録関数群
    ■更新日： 2018/10/26
    ■担　当： crew.miwa

    ■更新履歴：
     2018/01/19: 作成開始
     2018/10/26: 各メソッド名を変更
     
    */

class Db_lib
{
    // 定数を宣言
    const CREATE_STRING_NUM = 10;
    const CREATE_NUMBER_NUM = 10;
    const DEFAULT_SORT_ID = 1;
    const DEFAULT_SORT_COLUMN = 'sort_id';
    
    // スーパーオブジェクト割当用変数
    protected $CI;
    /*====================================================================
        コントラクト
    */
    public function __construct(){
        // ライブラリー読込み
        // CodeIgniter のスーパーオブジェクトを割り当て
        $this->CI =& get_instance();
    }
    /*====================================================================
        関数名： GetSelectValues
        概　要： データ一覧を取得
        引　数： $table_name : テーブル名
                 $target_key : 対象の値のカラム名
                 $public : ステータスフラグ
    */
    public function GetSelectValues ( $table_name, $target_key = 'name', $public = false )
    {
        // ソートキーをセット
        if ( $this->ColumnExists ( $table_name, self::DEFAULT_SORT_COLUMN ) )
        {
            $sort_key = self::DEFAULT_SORT_COLUMN;
        }
        else
        {
            $sort_key = $target_key;
        }
        
        $query = $this->CI->db->query("
            SELECT
                id,
                " . Base::add_slashes( $target_key ) . "
            FROM " . Base::add_slashes( $table_name ) . "
            " . ( $public ? "WHERE status >= " . Base::STATUS_ENABLE : "" ) . "
            ORDER BY " . Base::add_slashes( $sort_key ) . " ASC;
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() AS $row )
            {
                // CordIgniter用配列にセット
                $returnVal[$row->id] = $row->{$target_key};
            }
        }
        else {
            $returnVal = "";
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetValue
        概　要： 対象の値を取得
        引　数： $table_name : テーブル名
                 $column_name : 対象のカラム名
                 $where_val : WHERE対象の値
                 $where_key : WHERE対象の値のカラム名
                 $public : ステータスフラグ
    */
    public function GetValue ( $table_name, $column_name, $where_val, $where_key = 'id', $public = false )
    {
        $query = $this->CI->db->query("
            SELECT " . Base::add_slashes( $column_name ) . "
            FROM " . Base::add_slashes( $table_name ) . "
            WHERE (
                " . Base::add_slashes( $table_name ) . " . " . Base::add_slashes( $where_key ) . " = '" . Base::add_slashes( $where_val ) . "'
                " . ($public ? " AND status >= " . Base::STATUS_ENABLE : "") . "
            )
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0)
        {
            foreach ( $query->result() AS $row )
            {
                // 最初に取得した値を返り値としてはき出す
                return $row->{$column_name};
            }
        }
    }
    /*====================================================================
        関数名： GetValueMax
        概　要： 取得最大値を返す
        引　数： $table_name : テーブル名
                 $column_name : 対象のカラム名
                 $public : ステータスフラグ
    */
    public function GetValueMax ( $table_name, $column_name = self::DEFAULT_SORT_COLUMN, $public = false )
    {
        $query = $this->CI->db->query("
            SELECT MAX(" . Base::add_slashes( $column_name ) . ") AS sort_id_max
            FROM " . Base::add_slashes( $table_name ) . "
            " . ($public ? " WHERE status >= " . Base::STATUS_ENABLE : "") . "
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0)
        {
            foreach ( $query->result() AS $row )
            {
                // 最初に取得した値を返り値としてはき出す
                return $row->sort_id_max;
            }
        }
    }
    /*====================================================================
        関数名： GetMaxCount
        概　要： 取得合計数を返す
        引　数： $table_name : テーブル名
                 $whereSql : WHERE文（配列）
                 $public : ステータスフラグ
    */
    public function GetMaxCount ( $table_name, $whereSql = "", $public = false )
    {
        // 初期値をセット
        $returnVal = 0;
        
        if ( $public )
        {
            $whereSql[] = Base::add_slashes( $table_name ) . " . status >= " . Base::STATUS_ENABLE;
        }
        // SQLクエリ
        $query = $this->CI->db->query("
            SELECT COUNT(*) AS count
            FROM " . Base::add_slashes( $table_name ) . "
            ".( is_array( $whereSql ) > 0 ? " WHERE ( ".@implode ( " AND ", $whereSql )." ) " : "" )."
        ");
        // 結果が、空でない場合
        if ($query->num_rows() > 0)
        {
            $returnVal = $query->row()->count;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： ColumnExists
        概　要： カラムが存在するかどうか
        引　数： $table_name : テーブル名
                 $column_name : 対象のカラム名
    */
    public function ColumnExists ( $table_name, $column_name )
    {
        $query = $this->CI->db->query("
            SHOW COLUMNS FROM " . Base::add_slashes( $table_name ) . " LIKE '" . Base::add_slashes( $column_name ) . "'
        ");
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() AS $row )
            {
// print_r ($row);
                // 最初に取得した値を返り値としてはき出す
                return ( $row->Field != '' ? true : false );
            }
        }
    }
    /*====================================================================
        関数名： ValueExists
        概　要： 値が存在するかどうか
        引　数： $table_name : テーブル名
                 $where_val : WHERE対象の値
                 $where_key : WHERE対象の値のカラム名
                 $public : ステータスフラグ
    */
    public function ValueExists ( $table_name, $where_val, $where_key = 'id', $public = false )
    {
        $query = $this->CI->db->query("
            SELECT COUNT(*) AS count
            FROM " . Base::add_slashes( $table_name ) . "
            WHERE (
                " . Base::add_slashes( $table_name ) . " . " . Base::add_slashes( $where_key ) . " = '" . Base::add_slashes( $where_val ) . "'
                " . ( $public ? " AND " . Base::add_slashes( $table_name ) . " . status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() AS $row )
            {
                // 最初に取得した値を返り値としてはき出す
                return ( $row->count > 0 ? true : false );
            }
        }
    }
    /*====================================================================
        関数名： SameExists
        概　要： 対象ID以外に同じ値が存在するかどうか
        引　数： $table_name : テーブル名
                 $target_val : WHERE対象の値（同値確認）
                 $target_key : WHERE対象の値のカラム名（同値確認）
                 $where_val : WHERE対象の値（対象キー）
                 $where_key : WHERE対象の値のカラム名（対象キー）
                 $public : ステータスフラグ
    */
    public function SameExists ( $table_name, $target_val = '', $target_key = 'name', $where_val = '', $where_key = 'id', $public = false )
    {
        $query = $this->CI->db->query("
            SELECT COUNT(*) AS count
            FROM " . Base::add_slashes( $table_name ) . "
            WHERE (
                " . ( $where_key != "" ? Base::add_slashes( $where_key ) . " .  <> '" . Base::add_slashes( $target_val ) . "' AND" : "" ) . "
                " . Base::add_slashes( $target_key ) . " = '" . Base::add_slashes( $target_val ) . "'
                " . ( $public ? " AND status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() AS $row )
            {
                // 最初に取得した値を返り値としてはき出す
                return ( $row->count > 0 ? true : false );
            }
        }
    }
    /*====================================================================
        関数名： CreateStr
        概　要： 未使用の大文字小文字の英数字文字列を生成を取得
        引　数： $table_name : テーブル名
                 $where_key : WHERE対象の値のカラム名
                 $create_num : 生成する文字数
                 $public : ステータスフラグ
    */
    public function CreateStr ( $table_name, $where_key = 'id', $create_num = "", $public = false )
    {
        // 文字列用ヘルパー関数
        $this->load->helper('string');
        // 文字数をセット
        $create_num = ( $create_num != "" ? $create_num : self::CREATE_STRING_NUM );
        
        do{
            // 文字列生成
            $create_str = random_string( 'alnum', $create_num );
        } while ( $this->ValueExists( $table_name, $create_str, $where_key, $public ) );
        
        return $create_str;
    }
    /*====================================================================
        関数名： CreateNum
        概　要： 未使用の数字文字列を生成を取得
        引　数： $table_name : テーブル名
                 $where_key : WHERE対象の値のカラム名
                 $create_num : 生成する文字数
                 $public : ステータスフラグ
    */
    public function CreateNum ( $table_name, $where_key = 'id', $create_num = "", $public = false )
    {
        // 文字列用ヘルパー関数
        $this->load->helper('string');
        // 文字数をセット
        $create_num = ( $create_num != "" ? $create_num : self::CREATE_NUMBER_NUM );
        
        do{
            // 文字列生成
            $create_str = random_string( 'numeric', $create_num );
        } while ( $this->ValueExists( $table_name, $create_str, $where_key, $public ) );
        
        return $create_str;
    }
    /*====================================================================
        関数名： CreateSortId
        概　要： 順番の最大値＋１を取得
        引　数： $table_name : テーブル名
                 $public : ステータスフラグ
    */
    public function CreateSortId ( $table_name, $column_name = self::DEFAULT_SORT_COLUMN, $public = false )
    {
        // 順番最大値を取得
        $sort_id_max = $this->GetValueMax ( $table_name, $column_name, $public );
        // 順番最大値が取得できれば、＋１を、取得できなければ初期値を返す
        return ( $sort_id_max ? $sort_id_max + 1 : self::DEFAULT_SORT_ID );
    }
    /*====================================================================
        関数名： Insert
        概　要： DB新規追加
        引　数： $table_name : テーブル名
                 $values : 更新情報（オブジェクトまたは配列）
    */
    public function Insert ( $table_name, $values )
    {
        // オブジェクト又は、配列の場合のみ実行
        if (
            is_object ( $values ) ||
            is_array ( $values )
        )
        {
            // 登録情報をセット
            foreach ( $values AS $values_key => $values_val )
            {
                $form_values[$values_key] = $values_val;
            }
            // ステータス
            if (
                $this->ColumnExists ( $table_name, 'status' ) &&
                ! isset ( $values['status'] )
            )
            {
                // カラムが存在する場合、値をセットする
                $form_values['status'] = Base::STATUS_ENABLE;
            }
            // 順番
            if ( $this->ColumnExists ( $table_name, self::DEFAULT_SORT_COLUMN ) )
            {
                // カラムが存在する場合、値をセットする
                $form_values['sort_id'] = $this->CreateSortId ( $table_name, self::DEFAULT_SORT_COLUMN, true );
            }
            // 登録日時
            $form_values['regist_date'] = Base::now();
            // 更新日時
            $form_values['edit_date'] = Base::now();
            
            // トランザクション開始
            $this->CI->db->trans_start();
            // 新規登録処理
            $returnVal = $this->CI->db->insert( $table_name, $form_values );
            // トランザクション終了
            $this->CI->db->trans_complete();
            
            return $returnVal;
        }
    }
    /*====================================================================
        関数名： Update
        概　要： DB更新
        引　数： $table_name : テーブル名
                 $values : 更新情報（オブジェクトまたは配列）
                 $where_val : WHERE対象の値
                 $where_key : WHERE対象の値のカラム名
    */
    public function Update ( $table_name, $values, $where_val, $where_key = 'id' )
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object ( $values ) ||
            is_array ( $values )
        )
        {
            // 登録情報をセット
            foreach ( $values AS $values_key => $values_val )
            {
                $form_values[$values_key] = $values_val;
            }
            // 更新日時
            $form_values['edit_date'] = Base::now();
            
            // トランザクション開始
            $this->CI->db->trans_start();
            // WHERE情報をセット
            $this->CI->db->where( $where_key, $where_val );
            // 更新処理
            $returnVal = $this->CI->db->update( $table_name, $form_values );
            // トランザクション終了
            $this->CI->db->trans_complete();
            
            return $returnVal;
        }
        
    }
    /*====================================================================
        関数名： UpdateNoEscape
        概　要： DB更新（エスケープ回避）
        引　数： $table_name : テーブル名
                 $values : 更新情報（オブジェクトまたは配列）
                 $where_val : WHERE対象の値
                 $where_key : WHERE対象の値のカラム名
    */
    public function UpdateNoEscape ( $table_name, $values, $where_val, $where_key = 'id' )
    {
        // オブジェクトまたは配列の場合のみ実行
        if (
            is_object ( $values ) ||
            is_array ( $values )
        )
        {
            // 登録情報をセット
            foreach ( $values AS $values_key => $values_val )
            {
                $this->CI->db->set ( $values_key, $values_val, false );
            }
            // 更新日時
            $this->CI->db->set ( 'edit_date', Base::now(), true );
            
            // トランザクション開始
            $this->CI->db->trans_start();
            // WHERE情報をセット
            if ( is_array ( $where_val ) )
            {
                for ( $i = 0, $n = count ( $where_key ); $i < $n; $i ++ )
                {
                    $where[$where_key[$i]] = $where_val[$i];
                }
            }
            else
            {
                    $where[$where_key] = $where_val;
            }
            $this->CI->db->where( $where );
            // 更新処理
            $returnVal = $this->CI->db->update( $table_name );
            // トランザクション終了
            $this->CI->db->trans_complete();
            
            return $returnVal;
        }
        
    }
    /*====================================================================
        関数名： Delete
        概　要： DB削除
        引　数： $table_name : テーブル名
                 $logic : 論理削除フラグ
                 $where_val : WHERE対象の値
                 $where_key : WHERE対象の値のカラム名
    */
    public function Delete ( $table_name, $logic = true, $where_val, $where_key = 'id' )
    {
        // 削除対象の値がセットされている場合
        if ( $where_val != '' )
        {
            // 論理削除
            if ( $logic )
            {
                // 更新情報をセット
                $form_values['status'] = Base::STATUS_DISABLE;
                
                // トランザクション開始
                $this->CI->db->trans_start();
                // WHERE情報をセット
                $this->CI->db->where( $where_key, $where_val );
                // 削除処理（論理）
                $returnVal = $this->CI->db->update( self::MASTER_TABLE, $form_values );
                // トランザクション終了
                $this->CI->db->trans_complete();
                
                return $returnVal;
            }
            // 物理削除
            else
            {
                // トランザクション開始
                $this->CI->db->trans_start();
                // WHERE情報をセット
                $this->CI->db->where( $where_key, $where_val );
                // 削除処理
                $returnVal = $this->CI->db->delete( $table_name );
                // トランザクション終了
                $this->CI->db->trans_complete();
                
                return $returnVal;
            }
        }
    }
}
?>