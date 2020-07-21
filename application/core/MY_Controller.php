<?php
/**
 * 共通コントローラー
 * 更新日：2019/08/26
 * 更新履歴：
 *  2019/08/26：作成開始
 */
class MY_Controller extends CI_Controller
{
    // コンストラクタ
    public function __construct()
    {
        // Controllerクラスのコンストラクタを呼び出す
        parent::__construct();

        // バリデーションエラー時のタグを指定
        $this->form_validation->set_error_delimiters('<div class="form_error">', '</div>');

        // モデル呼出し
        

    }
}
