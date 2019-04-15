<?php
/*
■機　能： TOPICS用ライブラリ
■概　要： TOPICS用関連全般
■更新日： 2019/01/15
■担　当： crew.miwa

■更新履歴：
 2019/01/15: 作成開始
*/

class Topics_model extends CI_Model
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
        $this->load->library( 'topics_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // 一覧リストカウント数を初期化
        $resultVal['list_count'] = 0;
        
        // ラジオボタン情報をセット
        $resultVal['status_list'] = $this->topics_lib->StatusListValues ();
        // TOPICS情報を取得
        $place_id = $this->GetSessionPlaceId ();
        $resultVal['topics_list'] = $this->topics_lib->ListValues ( $place_id );
        
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
        // 各ライブラリの読込み
        $this->load->library( 'topics_lib' );
        $this->load->library( 'paper_lib' );
        
        // 返値を初期化
        $resultVal = array();
        // サブミットボタン
        $submit_conf_btn = $this->input->post_get( 'submit_conf_btn', true );
        
        // 会場ID情報を取得
        $resultVal['place_id'] = $this->GetSessionPlaceId ();
        
        // ID情報を取得
        $resultVal['topics_id'] = $this->input->post_get( 'topics_id', true );
        
        // ラジオボタン情報をセット
        $resultVal['paper_type_list'] = $this->paper_lib->ListValues ();
        
        // FORM情報をセット
        $form = $this->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $resultVal['form'][$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        // 確認画面、入力画面戻り処理
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
            if ( $this->topics_lib->IdExists ( $resultVal['topics_id'], true ) )
            {
                // 登録データを取得
                $regData = $this->topics_lib->DetalVal ( $resultVal['topics_id'], true );
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
                $resultVal['topics_id'] &&
                $this->upload_lib->FileExists ( $files[$i] . DIRECTORY_SEPARATOR . $resultVal['topics_id'] )
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
        関数名： EditStatusAction
        概　要： データ更新処理（ステータスのみ）
    */
    public function EditStatusAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'topics_lib' );
       // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $id = $this->input->post_get ( 'id', true );
        $formVal['status'] = $this->input->post_get ( 'status', true );
        
        // 更新処理
        $topics_id = $this->topics_lib->Regist ( $id, $formVal );
        $returnVal = ( $topics_id != '' ? true : false );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： EditAction
        概　要： データ更新処理
    */
    public function EditAction ()
    {
        // ライブラリー呼出し
        $this->load->library( 'topics_lib' );
        // 返値を初期値
        $returnVal = false;
        
        // FORM情報をセット
        $topics_id = $this->input->post_get ( 'topics_id', true );
        $formVal['place_id'] = $this->GetSessionPlaceId ();
        // 建物IDをセット
        $form = $this->FormInput ();
        for ( $i = 0, $n = count ($form); $i < $n; $i ++ )
        {
            $formVal[$form[$i]] = $this->input->post_get ( $form[$i], true );
        }
        
        // 更新処理
        $topics_id = $this->topics_lib->Regist ( $topics_id, $formVal );
        
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
                $regist_file = $files[$i] . DIRECTORY_SEPARATOR . $topics_id;
                // 本登録用ファイルを保存
                $file_name = $this->upload_lib->UploadFileMain ( $file_temp_path, $regist_file );
            }
        }
        
        return $returnVal;
    }
    /*====================================================================
        関数名： GetSessionPlaceId
        概　要： SESSIONデータ取得処理（会場ID）
    */
    public function GetSessionPlaceId ()
    {
        // 返値を初期値
        $returnVal = false;
        
        // ライブラリー呼出し
        $this->load->library( 'admin_lib' );
        
        // 登録済みの情報をセット
        $returnVal = $this->session_lib->GetSessionVal ( Login_model::AUTH_ADMIN . '_place_id' );
        
        return $returnVal;
    }
    /*====================================================================
        関数名： DelAction
        概　要： ジャンルデータ削除処理
    */
    public function DelAction ()
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
        $listVal['status_list'] = $this->topics_lib->StatusListValues ();
        // テンプレート
        $returnVal['list'] = $this->load->view( Login_model::AUTH_ADMIN . '/topics_list_part', $listVal, true);
        
        return $returnVal;
    }
    public function FormInput ()
    {
        $returnVal = array (
            'paper_type',
            'title',
            'title_sub',
            'start',
            'end',
            'closing',
            'body',
            'next',
            'memo',
            'caption',
        );
        return $returnVal;
    }
    public function FileInput ()
    {
        $returnVal = array (
            'topics_img',
        );
        return $returnVal;
    }
}