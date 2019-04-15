<?php
/*
■機　能： ジャンル画面用モデル
■概　要： 
■更新日： 2019/01/18
■担　当： crew.miwa

■更新履歴：
 2019/01/18: 作成開始
*/

class Genre_model extends CI_Model {
    
    const DEFAULT_LIST_COUNT = 200;
    const FIRST_MSG = '検索項目を選択してください。';
    const NO_LIST_MSG = '一覧リストが見つかりません。';
    /*====================================================================
        関数名： ListTemplate
        概　要： 一覧テンプレート情報を取得
    */
    public function ListTemplate ()
    {
        // 各ライブラリの読み込み
        $this->load->library( 'genre_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // 一覧リストカウント数を初期化
        $resultVal['list_count'] = 0;
        
        // ジャンル情報を取得
        $resultVal['genre_list'] = $this->genre_lib->ListValues ( '', true );
        
        // FROM値の有無によって表示内容を変更してセット
        $resultVal['no_list_msg'] = self::NO_LIST_MSG;
        
        return $resultVal;
     }
    /*====================================================================
        関数名： InputTemplate
        概　要： 入力テンプレート情報を取得
    */
    public function InputTemplate ()
    {
        // 各ライブラリの読み込み
        $this->load->library( 'genre_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // サブミットボタン
        $submit_input_btn = $this->input->post_get( 'submit_input_btn', true );
        $submit_conf_btn = $this->input->post_get( 'submit_conf_btn', true );
        
        // ID情報を取得
        $resultVal['target_id'] = $this->input->post_get( 'target_id', true );
        // FORM情報をセット
        $form = $this->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $resultVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        if ( ! $submit_input_btn && ! $submit_conf_btn )
        {
            // 登録データがあるかどうか
            if ( $this->genre_lib->IdExists ( $resultVal['target_id'], true ) )
            {
                // 登録データを取得
                $regData = $this->genre_lib->DetalVal ( $resultVal['target_id'], true );
                // 登録情報をセット
                $resultVal['form']['name'] = $regData['name'];
            }
        }
        
        return $resultVal;
     }
    /*====================================================================
        関数名： EditSortAction
        概　要： データ更新処理（ソートのみ）
    */
    public function EditSortAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'genre_lib' );
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
                $this->genre_lib->EditSort ( $id, $j );
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditNameAction
        概　要： データ更新処理（ジャンル名）
    */
    public function EditNameAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'genre_lib' );
       // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $target_id = $this->input->post_get ( 'target_id', true );
        $formVal['name'] = $this->input->post_get ( 'name', true );
        // 更新処理
        $this->genre_lib->Regist ( $target_id, $formVal );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： DelAction
        概　要： ジャンルデータ削除処理
    */
    public function DelAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'genre_lib' );
       // 返値を初期値
        $returnVal = false;
        // FORM情報をセット
        $id = $this->input->post_get ( 'id', true );
        // 削除処理
        $returnVal['result'] = $this->genre_lib->SelectDelete ( $id );
        // 削除完了後の情報を取得
        $listVal['list'] = $this->genre_lib->ListValues ( '', true );
        $returnVal['list'] = $this->load->view( Login_model::AUTH_OWNER . '/genre_list_part', $listVal, true);
        
        return $returnVal;
    }
    /*====================================================================
        関数名： FormList
        概　要： フォーム用配列
    */
    public function FormList ()
    {
        $returnVal = array (
            'no',
            'start',
            'end',
        );
        return $returnVal;
    }
    public function FormInput ()
    {
        $returnVal = array (
            'name',
        );
        return $returnVal;
    }
}