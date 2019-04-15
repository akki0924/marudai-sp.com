<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： 刊行情報用ライブラリ
    ■概　要： 刊行情報用関連全般
    ■更新日： 2019/01/17
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/17: 作成開始
     
    */

class Place_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_place";
    
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
        関数名： DetalVal
        概　要： 詳細データを取得
    */
    public function DetalVal ( $id = "", $public = false )
    {
        // 各ライブラリの読み込み
        $this->CI->load->library( 'topics_lib' );
        
        // 返値を初期化
        $returnVal = array ();
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . account,
                " . self::MASTER_TABLE . " . password,
                " . self::MASTER_TABLE . " . type_id,
                " . self::MASTER_TABLE . " . url,
                " . self::MASTER_TABLE . " . tel,
                " . self::MASTER_TABLE . " . address,
                " . self::MASTER_TABLE . " . closing,
                " . self::MASTER_TABLE . " . lat,
                " . self::MASTER_TABLE . " . lng,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = " . Base::empty_to_null( $id ) . "
                " . ( $public ? " AND " . self::MASTER_TABLE . ".status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $result_list = $query->result_array();
            foreach ( $result_list[0] as $key => $val )
            {
                // 配列にセット
                $returnVal[$key] = $val;
            }
            // トピックス有無をセット
            $returnVal['topics_exists'] = ( $this->CI->topics_lib->PlaceIdExists ( $returnVal['id'], true ) ? true : false );
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： ListValues
        概　要： データ一覧を取得
    */
    public function ListValues ( $whereSql = "", $public = false )
    {
        // 返値を初期化
        $resultList = array();
        
        // WHERE文をセット
        if ( is_array ( $whereSql ) && count ( $whereSql ) > 0 )
        {
            foreach ( $whereSql AS $whereSql_val )
            {
                // 検索項目をセット
                $whereSql[] = $whereSql_val;
            }
        }
        if ( $public )
        {
            $whereSql[] = self::MASTER_TABLE . " . status > " . Base::STATUS_DISABLE;
        }
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name
            FROM " . self::MASTER_TABLE . "
            " . ( isset ( $whereSql ) && count ( $whereSql ) > 0 ? ( " WHERE ( " . @implode ( " AND ", $whereSql ) ) . " ) " : "" ) . "
            ORDER BY " . self::MASTER_TABLE . " . sort_id ASC;
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $resultList = $query->result_array();
            for ( $i = 0, $count_i = 0, $n = count ($resultList); $i < $n; $i ++ )
            {
                // 表示数をカウント
                $count_i ++;
                // 表示件数
                $pageVal['dispLine'] = $count_i;
            }
        }
        return $resultList;
    }
    /*====================================================================
        関数名： SelectNameValues
        概　要： ランク名一覧を取得
    */
    public function SelectNameValues ( $public = false )
    {
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： GetName
        概　要： 名前を取得
    */
    public function GetNo ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'name', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetSortId
        概　要： 順番を取得
    */
    public function GetSortId ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'sort_id', $id, 'id', $public );
    }
    /*====================================================================
        関数名： IdExists
        概　要： IDが存在するかどうか
    */
    public function IdExists ( $id, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $id, 'id', $public );
    }
    /*====================================================================
        関数名： Regist
        概　要： DB登録処理
    */
    public function Regist ( $id = '', $values )
    {
        $returnVal = "";
        // IDが登録されている場合
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
            // 返値をセット
            $returnVal = $id;
        }
        else
        {
            // IDを生成
            $values['id'] = $this->CI->db_lib->CreateSortId ( self::MASTER_TABLE, 'id' );
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
            // 返値をセット
            $returnVal = $values['id'];
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： EditSort
        概　要： DB更新処理（順番変更）
    */
    public function EditSort ( $id, $sort )
    {
        // IDが登録されている場合
        if ( $this->IdExists ( $id, true ) )
        {
            $values['sort_id'] = $sort;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
    }
    /*====================================================================
        関数名： EditSortList
        概　要： DB更新処理（順番変更）
    */
    public function EditSortList ( $sort_list )
    {
        // 現行リストを取得
//        $now_list = $this->ListValues ();
        
        for ( $i = 0, $n = count ( $sort_list ); $i < $n; $i ++ )
        {
            $now_sort_id = $this->GetSortId ( $sort_list[$i] );
            if ( $i == 0 )
            {
                $sort_id_first = $now_sort_id;
                $sort_id_first_tmp = $sort_id_first;
            }
            else
            {
                if ( ( $sort_id_first_tmp + $i ) > $now_sort_id )
                {
                    // 影響あるリストの更新
                    $values['sort_id'] = 'sort_id - 1';
                    $sort_id_first_tmp --;
                    $where_val[] = $sort_id_first_tmp + $i;
                    $where_key[] = 'sort_id <=';
                    $where_val[] = $now_sort_id;
                    $where_key[] = 'sort_id >';
                    $this->CI->db_lib->UpdateNoEscape ( self::MASTER_TABLE, $values, $where_val, $where_key );
                    
                    // ソート情報更新処理
                    $values['sort_id'] = $sort_id_first_tmp + $i;
                    $this->CI->db_lib->UpdateNoEscape ( self::MASTER_TABLE, $values, $sort_list[$i] );
                }
                else if (  ( $sort_id_first_tmp + $i ) < $now_sort_id  )
                {
                    // 影響あるリストの更新
                    $values['sort_id'] = 'sort_id + 1';
                    $where_val[] = $sort_id_first_tmp + $i;
                    $where_key[] = 'sort_id >=';
                    $where_val[] = $now_sort_id;
                    $where_key[] = 'sort_id <';
                    $this->CI->db_lib->UpdateNoEscape ( self::MASTER_TABLE, $values, $where_val, $where_key );
                    
                    // ソート情報更新処理
                    $values['sort_id'] = $sort_id_first_tmp + $i;
                    $this->CI->db_lib->UpdateNoEscape ( self::MASTER_TABLE, $values, $sort_list[$i] );
                }
            }
        }
    }
    /*====================================================================
        関数名： SelectDelete
        概　要： DB削除処理（論理削除）
    */
    public function SelectDelete ( $id )
    {
        // ジャンルIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            return $this->CI->db_lib->Delete ( self::MASTER_TABLE, false, $id );
        }
    }
}
?>