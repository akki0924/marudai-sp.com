<?php
/*
■機　能： 会場用ライブラリ
■概　要： 会場用関連全般
■更新日： 2019/01/15
■担　当： crew.miwa

■更新履歴：
 2019/01/15: 作成開始
*/

class Place_model extends CI_Model
{
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
        関数名： ListTemplate
        概　要： 一覧テンプレート情報を取得
    */
    public function ListTemplate ()
    {
        // 各ライブラリの読み込み
        $this->load->library( 'place_lib' );
        $this->load->library( 'type_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // 一覧リストカウント数を初期化
        $resultVal['list_count'] = 0;
        
        // 所属情報をセット
        $type_id = $this->EditSessionTypeIdAction ();
        $resultVal['type_name'] = $this->type_lib->GetName ( $type_id );
        
        // 会場情報を取得
        $place_list_whereSql[] = place_lib::MASTER_TABLE . " . type_id = " . $type_id;
        $resultVal['place_list'] = $this->place_lib->ListValues ( $place_list_whereSql, true );
        
        // FROM値の有無によって表示内容を変更してセット
        $resultVal['no_list_msg'] = self::NO_LIST_MSG;
        
        return $this->sharedTemplate ( $resultVal );
     }
    /*====================================================================
        関数名： InputTemplate
        概　要： 入力テンプレート情報を取得
    */
    public function InputTemplate ()
    {
        // 各ライブラリの読み込み
        $this->load->library( 'place_lib' );
        $this->load->library( 'type_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // サブミットボタン
        $submit_conf_btn = $this->input->post_get( 'submit_conf_btn', true );
        // 新規
        $new_flg = $this->input->post_get( 'place_new', true );
        // 新規登録の場合
        if ( $new_flg )
        {
            // SESSION登録の建物IDを初期化
            $this->ClearSessionPlaceIdAction ();
        }
        // ID情報を取得
        $resultVal['place_id'] = $this->EditSessionPlaceIdAction ();
        
        // プルダウン情報をセット
        $resultVal['type_id_list'] = $this->type_lib->ListValues ();
        
        // FORM情報をセット
        $form = $this->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $resultVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        // 確認画面処理
        if ( $submit_conf_btn )
        {
            // FILES情報をセット
            $files = $this->FileInput ();
            for ( $i = 0, $n = count ( $files ); $i < $n; $i ++ )
            {
                $resultVal['form'][$files[$i] . '_path'] = $this->input->post_get( $files[$i] . '_path', true );
                if ( $resultVal['form'][$files[$i] . '_path'] == '')
                {
                    // 仮登録用ファイルを生成（ファイル名を取得）
                    $file_name = $this->upload_lib->UploadFileTemp ( $files[$i] );
                    // 仮登録用ファイルパスをセット
                    if ( $file_name != '' )
                    {
                        $resultVal['form'][$files[$i] . '_path'] = $this->upload_lib->SrcWebTempPath ( $file_name );
                    }
                }
            }
        }
        if ( ! $submit_conf_btn )
        {
            // 登録データがあるかどうか
            if ( $this->place_lib->IdExists ( $resultVal['place_id'], true ) )
            {
                // 登録データを取得
                $regData = $this->place_lib->DetalVal ( $resultVal['place_id'], true );
                // 登録情報をセット
                $resultVal['form'] = $regData;
            }
        }
        // FILES情報をセット
        $files = $this->FileInput ();
        for ( $i = 0, $n = count ( $files ); $i < $n; $i ++ )
        {
            // ファイルの存在有無をセット
            if (
                $resultVal['place_id'] &&
                $this->upload_lib->FileExists ( $files[$i] . DIRECTORY_SEPARATOR . $resultVal['place_id'] )
            )
            {
                $resultVal[$files[$i] . '_exists'] = true;
            }
            else
            {
                $resultVal[$files[$i] . '_exists'] = false;
            }
        }
        
        return $this->sharedTemplate ( $resultVal );
     }
    /*====================================================================
        関数名： OutputPhotoTemplate ()
        概　要： 写真テンプレート情報を取得
    */
    public function OutputPhotoTemplate ()
    {
        // 出力バッファーをクリア（消去）する
        ob_clean();
        // ファイルをセット
        $image_file = $this->upload_lib->SrcPath () . "/" . $this->upload_lib->FileCheck ( $dir . "/" . $id );
        $this->output->set_content_type ( get_mime_by_extension ( $image_file ) );
        $this->output->set_output ( file_get_contents ( $image_file ) );
    }
    /*====================================================================
        関数名： EditSortAction
        概　要： データ更新処理（ソートのみ）
    */
    public function EditSortAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'place_lib' );
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
                $ids[] = $id_list_temp[1];
            }
            $this->place_lib->EditSortList ( $ids );
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditAction
        概　要： データ更新処理
    */
    public function EditAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'place_lib' );
        // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $place_id = $this->EditSessionPlaceIdAction ();
        $form = $this->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $formVal[$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        
        // 更新処理
        $place_id = $this->place_lib->Regist ( $place_id, $formVal );
        
        // ファイルアップロード処理
        $files = $this->FileInput ();
        for ( $i = 0, $n = count ( $files ); $i < $n; $i ++ )
        {
            $file_temp_path = $this->input->post_get( $files[$i] . '_path', true );
            if ( $file_temp_path == '')
            {
                // 仮登録用ファイルを生成（ファイル名を取得）
                $file_name = $this->upload_lib->UploadFileTemp ( $files[$i] );
                // 仮登録用ファイルパスをセット
                if ( $file_name != '' )
                {
                    $file_temp_path = $this->upload_lib->SrcWebTempPath ( $file_name );
                }
            }
            if ( $file_temp_path != '')
            {
                // 登録先ファイルパスをセット
                $regist_file = $files[$i] . DIRECTORY_SEPARATOR . $place_id;
                // 本登録用ファイルを保存
                $file_name = $this->upload_lib->UploadFileMain ( $file_temp_path, $regist_file );
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditSessionPlaceIdAction
        概　要： SESSIONデータ更新処理（会場ID）
    */
    public function EditSessionPlaceIdAction ()
    {
        // 返値を初期値
        $returnVal = false;
        
        // ライブラリー呼出し
        $this->load->library( 'admin_lib' );
        
        // 会場IDを取得
        $place_id = $this->input->post( 'place_id', true );
        
        if ( $place_id != '' )
        {
            // 会場IDをSESSION登録
            $this->session_lib->SetSessionVal ( Login_model::AUTH_ADMIN . '_place_id', $place_id );
            $returnVal = $place_id;
        }
        else
        {
            // 登録済みの情報をセット
            $place_id = $this->session_lib->GetSessionVal ( Login_model::AUTH_ADMIN . '_place_id' );
            $returnVal = $place_id;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： ClearSessionPlaceIdAction
        概　要： SESSIONデータ削除処理（会場ID）
    */
    public function ClearSessionPlaceIdAction ()
    {
        // 返値を初期値
        $returnVal = false;
        
        // ライブラリー呼出し
        $this->load->library( 'admin_lib' );
        // 削除処理
        $this->session_lib->ClearSessionVal ( Login_model::AUTH_ADMIN . '_place_id' );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditSessionTypeIdAction
        概　要： SESSIONデータ更新処理（所属）
    */
    public function EditSessionTypeIdAction ()
    {
        // 返値を初期値
        $returnVal = false;
        
        // ライブラリー呼出し
        $this->load->library( 'admin_lib' );
        $this->load->library( 'type_lib' );
        
        // タイプIDを取得
        $type_id = $this->input->post( 'type_id', true );
        
        if ( $type_id != '' )
        {
            // タイプIDをSESSION登録
            $this->session_lib->SetSessionVal ( Login_model::AUTH_ADMIN . '_type_id', $type_id );
            $returnVal = $type_id;
        }
        else
        {
            // 登録済みの情報をセット
            $type_id = $this->session_lib->GetSessionVal ( Login_model::AUTH_ADMIN . '_type_id' );
            // 登録されていない場合
            if ( ! $type_id )
            {
                // 初期値をセット
                $type_id = Type_lib::ID_MUSEUM;
            }
            $returnVal = $type_id;
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： DelAction
        概　要： ジャンルデータ削除処理
    */
    public function DelAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'place_lib' );
       // 返値を初期値
        $returnVal = false;
        // FORM情報をセット
        $id = $this->input->post_get ( 'id', true );
        // 削除処理
        $returnVal['result'] = $this->place_lib->SelectDelete ( $id );
        // 削除完了後の情報を取得
        $place_list_whereSql[] = place_lib::MASTER_TABLE . " . type_id = " . $this->EditSessionTypeIdAction ();
        $listVal['list'] = $this->place_lib->ListValues ( $place_list_whereSql, true );
        $returnVal['list'] = $this->load->view( Login_model::AUTH_ADMIN . '/place_list_part', $listVal, true);
        
        return $returnVal;
    }
    public function FormInput ()
    {
        $returnVal = array (
            'account',
            'password',
            'name',
            'type_id',
            'address',
            'lat',
            'lng',
            'closing',
            'url',
            'tel',
        );
        return $returnVal;
    }
    public function FileInput ()
    {
        $returnVal = array (
            'place_img',
        );
        return $returnVal;
    }
}