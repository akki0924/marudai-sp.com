<?php
/*
■機　能： TOP画面用モデル
■概　要： 
■更新日： 2019/01/18
■担　当： crew.miwa

■更新履歴：
 2019/01/18: 作成開始
*/

class Main_model extends CI_Model {
    
    const DEFAULT_LIST_COUNT = 200;
    const FIRST_MSG = '検索項目を選択してください。';
    const NO_LIST_MSG = '一覧リストが見つかりません。';
    
    /*====================================================================
        関数名： sharedTemplate
        概　要： 共通テンプレート情報を取得
    */
    function sharedTemplate ( $returnVal = "" )
    {
        // 各ライブラリの読み込み
        $this->load->library( 'admin_lib' );
        $this->load->library( 'type_lib' );
        
        // ログイン情報をセット
        $id = $this->session_lib->GetSessionVal ( Login_model::AUTH_ADMIN . '_id' );
        $templateVal['name'] = $this->admin_lib->name ( $id, true );
        
        // タイプ一覧情報をセット
        $templateVal['type_list'] = $this->type_lib->ListValues ( true );
        
        // 変数を再セット
        $returnVal = ( $returnVal != "" ? $returnVal : array () );
        // 各テンプレートをセット
        $returnVal['header_tpl'] = $this->load->view ( Login_model::AUTH_ADMIN . '/header', $templateVal, true );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： TopTemplate
        概　要： TOPページテンプレート情報を取得
    */
    public function TopTemplate ( $formVal )
    {
        // 各ライブラリの読み込み
        $this->load->library( 'publication_lib' );
        $this->load->library( 'topics_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // 一覧リストカウント数を初期化
        $resultVal['list_count'] = 0;
        
        // 刊行情報を取得
        $resultVal['publication'] = $this->publication_lib->DetalVal ( true );
        // トピックス情報を取得
        $resultVal['topics_list'] = $this->topics_lib->ListValues ( '', true );
        
        // Form値をセット
        $resultVal['form'] = $formVal;
        
/*
        // 媒介IDがセットされている場合のみ、選択媒介を単独表示
        if ( $formVal['object'] != '' && $this->object_lib->IdExists ( $formVal['object'], true ) )
        {
            // 媒介情報
            $resultVal['list'][0]['object']['id'] = $formVal['object'];
            $resultVal['list'][0]['object']['name'] = $this->object_lib->GetName ( $formVal['object'], true );
            // 対象一覧リスト
            $resultVal['list'][0]['item'] = $this->TopListValues ( $formVal['keyword'], $formVal['object'], $formVal['tag'], $formVal['rank'], true );
            // 一覧リストカウント数
            $resultVal['list_count'] += count ( $resultVal['list'][0]['item'] );
        }
        // キーワード、または評価がセットされている場合
        else if (
            $formVal['keyword'] != '' ||
            ( $formVal['rank'] != '' && $this->rank_lib->IdExists ( $formVal['rank'], true ) )
        )
        {
            $i = 0;
            foreach ( $object_list AS $object_key => $object_val )
            {
                // 媒介情報
                $resultVal['list'][$i]['object']['id'] = $object_key;
                $resultVal['list'][$i]['object']['name'] = $object_val;
                // 対象一覧リスト
                $resultVal['list'][$i]['item'] = $this->TopListValues ( $formVal['keyword'], $object_key, $formVal['tag'], $formVal['rank'], true );
                // 一覧リストカウント数
                $resultVal['list_count'] += count ( $resultVal['list'][$i]['item'] );
                $i ++;
            }
        }
*/
        // FORMに値がセットされているか
        $formInputFlg = false;
        foreach ( $formVal AS $formKey => $formValue )
        {
            if ( $formValue != '' )
            {
                $formInputFlg = true;
            }
        }
        // FROM値の有無によって表示内容を変更してセット
        $resultVal['no_list_msg'] = ( $formInputFlg ? self::NO_LIST_MSG : self::FIRST_MSG );
        
        return $this->sharedTemplate ( $resultVal );
     }
    /*====================================================================
        関数名： EditPublicationAction
        概　要： 刊行データ更新処理
    */
    public function EditPublicationAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'publication_lib' );
       // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $form = self::PublicationFormList ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $formVal[$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        // 更新処理
        $this->publication_lib->EditData ( $formVal );
        // 登録完了後の情報を取得
        $returnVal = $this->publication_lib->DetalVal ( true );
        
        return $returnVal;
    }
    // Form値をセット
    function PublicationFormList () {
        $returnVal[] = 'no';
        $returnVal[] = 'start';
        $returnVal[] = 'end';
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditTopicsSortAction
        概　要： トピックスデータ更新処理（ソートのみ）
    */
    public function EditTopicsSortAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'topics_lib' );
       // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $formVal['list'] = $this->input->post_get ( 'list', true );
        
        $sort_list = explode ( ',' , $formVal['list'] );
        if ( count ( $sort_list ) > 0 )
        {
            $returnVal = true;
            $n = count ( $sort_list );
            for ( $i = 0, $j = 1; $i < $n; $i ++, $j ++ )
            {
                $id_list_temp = explode ( '_' , $sort_list[$i] );
                // ID情報を抽出
                $id = $id_list_temp[1];
                // ソート情報更新処理
                $this->topics_lib->EditSort ( $id, $j );
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： DelTopicsAction
        概　要： トピックスデータ削除処理
    */
    public function DelTopicsAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'topics_lib' );
       // 返値を初期値
        $returnVal = false;
        // FORM情報をセット
        $id = $this->input->post_get ( 'id', true );
        // 削除処理
        $returnVal['result'] = $this->topics_lib->SelectDelete ( $id );
        // 削除完了後の情報を取得
        $listVal['list'] = $this->topics_lib->ListValues ( '', true );
        $returnVal['list'] = $this->load->view( Login_model::AUTH_ADMIN . '/main_topics_part', $listVal, true);
        
        return $returnVal;
    }
    /*====================================================================
        関数名： RankTemplate
        概　要： TOPページテンプレートにランク情報を更新して変更情報を返す
    */
    public function RankTemplate ( $formVal )
    {
        // 各ライブラリの読み込み
        $this->load->library( 'item_lib' );
        $this->load->library( 'rank_lib' );
        
        // 返値にFORM情報をセット
        $resultVal = $formVal;
        
        // ランク情報の更新
        $this->item_lib->EditRankId ( $formVal['id'], $formVal['rank_id'] );
        
        // ランク一覧を取得
        $rank_list = $this->rank_lib->SelectNameValues ( true );
        $resultVal['rank_count'] = count ( $rank_list );
        
        // FORM情報をセット
        $resultVal['rank_id'] = $formVal['rank_id'];
        $resultVal['rank_id'] = $formVal['rank_id'];
        
        return $resultVal;
     }
    /*====================================================================
        関数名： form_list
        概　要： フォーム用配列
    */
    public function form_list ()
    {
        $returnVal = array (
            'no',
            'start',
            'end',
        );
        return $returnVal;
    }
}