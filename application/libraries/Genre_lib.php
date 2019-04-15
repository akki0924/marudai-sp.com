<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： ジャンル用処理ライブラリー
    ■概　要： ジャンル用登録関数群
    ■更新日： 2019/01/18
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/18: 作成開始
     
    */

class Genre_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_genre";
    // 表示数
    const DEFAULT_LIST_COUNT = 30;
    
    const ID_OTHER = "g99";
    const ID_HEAD_STR = "g";
    
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
    public function DetalVal ( $id, $public = false )
    {
        // 返値を初期化
        $returnVal = array ();
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . name,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . ".edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = '" . Base::add_slashes( $id ) . "'
                " . ( $public ? " AND " . self::MASTER_TABLE . ".status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $result_list = $query->result_array();
            foreach ( $result_list[0] as $key => $val )
            {
                // CordIgniter用配列にセット
                $returnVal[$key] = $val;
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： ListValues
        概　要： データ一覧を取得
    */
    public function ListValues ( $public = false )
    {
        // 返値を初期化
        $resultList = array();
        
        // WHERE文をセット
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
        関数名： GetName
        概　要： 名前を取得
    */
    public function GetName ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'name', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetNameToId
        概　要： 名前からIDを取得
    */
    public function GetNameToId ( $name, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'id', $name, 'name', $public );
    }
    /*====================================================================
        関数名： GetIdMax
        概　要： IDの最大値を取得
    */
    public function GetIdMax ( $public = false )
    {
        // 返値を初期化
        $returnVal = "";
        
        $query = $this->CI->db->query("
            SELECT
                MAX(" . self::MASTER_TABLE . " . id) AS id_max
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id != '" . self::ID_OTHER . "'
                " . ( $public ? " AND " . self::MASTER_TABLE . ".status >= " . Base::STATUS_ENABLE : "" ) . "
            )
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() AS $row )
            {
                $returnVal = $row->id_max;
            }
        }
        return $returnVal;
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
        関数名： NameExists
        概　要： 名前が存在するかどうか
    */
    public function NameExists ( $name, $public = false )
    {
        return $this->CI->db_lib->ValueExists ( self::MASTER_TABLE, $name, 'name', $public );
    }
    /*====================================================================
        関数名： CreateId
        概　要： IDを生成
    */
    public function CreateId ( $public = false )
    {
        // 数字部分の切り抜き
        $maxid_num = substr ( $this->GetIdMax() , 1 );
        // 返値をセット
        $returnVal = self::ID_HEAD_STR . sprintf('%02d', ( (int) $maxid_num ) + 1 );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： Regist
        概　要： DB登録処理
    */
    public function Regist ( $id = '', $values )
    {
        // IDが登録されている場合
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
        else
        {
            // IDを生成
            $values['id'] = $this->CreateId ();
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
        }
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