<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /*
    ■機　能： トピックス用処理ライブラリー
    ■概　要： トピックス用登録関数群
    ■更新日： 2019/01/18
    ■担　当： crew.miwa

    ■更新履歴：
     2019/01/18: 作成開始
     
    */

class Topics_lib
{
    // DBテーブル
    const MASTER_TABLE = "d_place_topics";
    
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
                " . self::MASTER_TABLE . " . place_id,
                " . self::MASTER_TABLE . " . paper_type,
                " . self::MASTER_TABLE . " . title,
                " . self::MASTER_TABLE . " . title_sub,
                " . self::MASTER_TABLE . " . start,
                DATE_FORMAT(" . self::MASTER_TABLE . " . start, '%Y.%c.%e') AS start_disp,
                " . self::MASTER_TABLE . " . end,
                DATE_FORMAT(" . self::MASTER_TABLE . " . end, '%Y.%c.%e') AS end_disp,
                " . self::MASTER_TABLE . " . closing,
                " . self::MASTER_TABLE . " . body,
                " . self::MASTER_TABLE . " . next,
                " . self::MASTER_TABLE . " . memo,
                " . self::MASTER_TABLE . " . sort_id,
                " . self::MASTER_TABLE . " . status,
                " . self::MASTER_TABLE . " . regist_date,
                " . self::MASTER_TABLE . ".edit_date
            FROM " . self::MASTER_TABLE . "
            WHERE (
                " . self::MASTER_TABLE . ".id = " . Base::empty_to_null( $id ) . "
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
    public function ListValues ( $place_id = '', $public = false )
    {
        // 各ライブラリの読み込み
        $this->CI->load->library( 'place_lib' );
        
        // 返値を初期化
        $resultList = array();
        
        // WHERE文をセット
        if ( $place_id )
        {
            $whereSql[] = self::MASTER_TABLE . " . place_id = " . $this->CI->base->empty_to_null ( $place_id );
        }
        if ( $public )
        {
            $whereSql[] = self::MASTER_TABLE . " . status > " . Base::STATUS_DISABLE;
        }
        
        $query = $this->CI->db->query("
            SELECT
                " . self::MASTER_TABLE . " . id,
                " . self::MASTER_TABLE . " . place_id,
                DATE_FORMAT(" . self::MASTER_TABLE . " . start, '%Y年%c月%e日') AS start_disp,
                DATE_FORMAT(" . self::MASTER_TABLE . " . end, '%Y年%c月%e日') AS end_disp,
                " . Place_lib::MASTER_TABLE . " . address,
                " . Place_lib::MASTER_TABLE . " . tel
            FROM " . self::MASTER_TABLE . "
            LEFT OUTER JOIN " . Place_lib::MASTER_TABLE . " ON
                " . self::MASTER_TABLE . " . place_id = " . Place_lib::MASTER_TABLE . " . id
            " . ( isset ( $whereSql ) && count ( $whereSql ) > 0 ? ( " WHERE ( " . @implode ( " AND ", $whereSql ) ) . " ) " : "" ) . "
            ORDER BY " . self::MASTER_TABLE . " . sort_id ASC;
        ");
        // 結果が、空でない場合
        if ( $query->num_rows() > 0 )
        {
            $resultList = $query->result_array();
            for ( $i = 0, $count_i = 0, $n = count ($resultList); $i < $n; $i ++ )
            {
                // タグ情報を配列化
                $resultList[$i]['tag_list'] = json_decode ( $resultList[$i]['tag'], true );
                // 表示数をカウント
                $count_i ++;
                // 表示件数
                $pageVal['dispLine'] = $count_i;
                // 表示数が最大値まで来たら、ループ終了
                if ($count_i >= self::DEFAULT_LIST_COUNT) {
                    break;
                }
            }
        }
        return $resultList;
    }
    /*====================================================================
        関数名： ListValuesByObject
        概　要： 媒介毎のデータ一覧を取得
    */
    public function ListValuesByObject ( $public = false )
    {
        // 各ライブラリの読み込み
        $this->CI->load->library( 'maker_lib' );
        $this->CI->load->library( 'object_lib' );
        $this->CI->load->library( 'tag_lib' );
        $this->CI->load->library( 'rank_lib' );
        
        // 返値を初期化
        $resultList = array();
        // 媒介一覧を取得
        $object_list = $this->CI->object_lib->SelectNameValues ( $public ); 
        
        $i = 0;
        foreach ( $object_list AS $object_key => $object_val )
        {
            // 媒介情報
            $resultList[$i]['object']['id'] = $object_key;
            $resultList[$i]['object']['name'] = $object_val;
            // 対象一覧リスト
            $resultList[$i]['list'] = $this->ListValues ( $object_key, $public );
            $i ++;
        }
        
        return $resultList;
    }
    /*====================================================================
        関数名： SelectNameValues
        概　要： トピックス名一覧を取得
    */
    public function SelectNameValues ( $public = false )
    {
        return $this->CI->db_lib->GetSelectValues ( self::MASTER_TABLE, 'name',  $public );

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
        関数名： GetIdOrNameToId
        概　要： ID、または名前からIDを取得
    */
    public function GetIdOrNameToId ( $id = '', $name = '', $public = false )
    {
        // IDが登録されておらず、名前がセットされている場合
        if ( ! $this->IdExists ( $id, $public ) && isset( $name ) )
        {
            // 名前からIDを取得
            $returnVal = $this->GetNameToId ( $name, $public );
        }
        else
        {
            // IDをそのまま返す
            $returnVal = $id;
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetRankId
        概　要： ランクを取得
    */
    public function GetRankId ( $id, $public = false )
    {
        return $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'rank_id', $id, 'id', $public );
    }
    /*====================================================================
        関数名： GetTag
        概　要： タグ情報を取得
    */
    public function GetTag ( $id, $array_flg = false, $public = false )
    {
        // 値を取得
        $returnVal = $this->CI->db_lib->GetValue ( self::MASTER_TABLE, 'tag', $id, 'id', $public );
        // 配列フラグがセットされている場合
        if ( $array_flg )
        {
            // JSON文字列をデコードして返す
            $returnVal = json_decode ( $returnVal, true );
        }
        return $returnVal;
    }
    /*====================================================================
        関数名： GetAddTagJson
        概　要： タグ情報をDB登録されているタグ情報に追加し取得
    */
    public function GetAddTagJson ( $id = "", $tag, $public = false )
    {
        // 登録された情報の場合
        if ( $this->IdExists ( $id, $public ) )
        {
            // 登録されたタグ情報をセット
            $tag_base = $this->GetTag ( $id, true, $public );
        }
        // 登録されたタグが存在する場合
        if (  isset ( $tag_base ) && count ( $tag_base ) > 0 )
        {
            // 返値用配列にセット
            $returnList = $tag_base;
            
            // セットされたタグを確認
            if ( isset ( $tag ) && count ( $tag ) > 0 )
            {
                foreach ( $tag as $tag_val )
                {
                    // 登録されたタグに、セットされるタグが存在しなければ
                    if ( ! in_array ( $tag_val, $tag_base ) )
                    {
                        // 返値用配列に値を追加
                        $returnList[] = $tag_val;
                    }
                }
            }
        }
        else
        {
            // タグ情報をそのままセット
            $returnList = $tag;
        }
        // JSONデータに変換して返す
        return json_encode ( $returnList );
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
        関数名： Regist
        概　要： DB登録処理
    */
    public function Regist ( $id = '', $values )
    {
        // ID、または名前からIDを取得
        if ( isset ($values['name']) )
        {
            $id = $this->GetIdOrNameToId ( $id, $values['name'], true );
        }
        // タグ情報がセットされている場合
        if( isset( $values['tag'] ) )
        {
            // タグ情報をJSON形式に追加セット
            $values['tag'] = $this->GetAddTagJson ( $id, $values['tag'], true );
        }
        // IDが登録されている場合
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $values, $id );
        }
        else
        {
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $values );
        }
    }
    /*====================================================================
        関数名： RegistName
        概　要： DB登録処理
    */
    public function RegistName ( $id = '', $name, $public = false )
    {
        // ID、または名前からIDを取得
        $id = $this->GetIdOrNameToId ( $id, $name, $public );
        // タグIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新情報をセット
            $regist_data['name'] = $name;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $regist_data, $id );
        }
        else 
        {
            // DB登録処理（名前から登録）メソッドを実行
            $this->RegistForName ( $name );
        }
    }
    /*====================================================================
        関数名： RegistForName
        概　要： DB登録処理（名前から登録）
    */
    public function RegistForName ( $name, $public = false  )
    {
        // トピックス名が登録されていない時に限り、登録処理
        if ( ! $this->NameExists ( $name ) )
        {
            $regist_data['name'] = $name;   // トピックス名
            // 新規登録処理
            $this->CI->db_lib->Insert ( self::MASTER_TABLE, $regist_data );
        }
    }
    /*====================================================================
        関数名： EditRankId
        概　要： DB更新処理（ランク変更）
    */
    public function EditRankId ( $id, $rank_id )
    {
        // トピックスIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新情報をセット
            $regist_data['rank_id'] = $rank_id;
            // 更新処理
            $this->CI->db_lib->Update ( self::MASTER_TABLE, $regist_data, $id );
        }
    }
    /*====================================================================
        関数名： SelectDelete
        概　要： DB削除処理（論理削除）
    */
    public function SelectDelete ( $id )
    {
        // トピックスIDが登録されているか
        if ( $this->IdExists ( $id, true ) )
        {
            // 更新処理
            return $this->CI->db_lib->Delete ( self::MASTER_TABLE, true, $id );
        }
    }
}
?>