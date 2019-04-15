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

class Publication_lib
{
    // DBテーブル
    const MASTER_TABLE = "m_publication";
    // 初期ID
    const DEFAULT_ID = 1;
    
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
    public function DetalVal ( $public = false )
    {
        $returnVal = array ();
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . no,
                " . self::MASTER_TABLE . ".sort_id,
                " . self::MASTER_TABLE . ".status,
                DATE_FORMAT(" . self::MASTER_TABLE . ".period_start, '%Y.%c.%e') AS period_start_disp,
                DATE_FORMAT(" . self::MASTER_TABLE . ".period_end, '%Y.%c.%e') AS period_end_disp,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . " . edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . " . id = " . self::DEFAULT_ID . "
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
        関数名： SelectNameValues
        概　要： ランク名一覧を取得
    */
    public function SelectNameValues ( $public = false )
    {
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： SelectNameTopicsValues
        概　要： TOPICS設定のタイトル一覧を取得
    */
    public function SelectTopicsValues ( $public = false )
    {
        
        $query = $this->CI->db->query("
            SELECT
                id,
                topics_title,
                
                " . Base::add_slashes( $target_key ) . "
            FROM " . Base::add_slashes( $table_name ) . "
            " . ( $public ? "WHERE status >= " . Base::STATUS_ENABLE : "" ) . "
            ORDER BY sort_id ASC;
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
    
    
    
    
    
    
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );
    }
    /*====================================================================
        関数名： GetNo
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
        関数名： EditData
        概　要： DB更新処理（単独データの為、ID確認作業は省き更新のみ）
    */
    public function EditData ( $regist_data )
    {
        // IDに初期値をセット
        $id = self::DEFAULT_ID;
        // 更新処理
        $this->CI->db_lib->Update ( self::MASTER_TABLE, $regist_data, $id );
    }
}
?>