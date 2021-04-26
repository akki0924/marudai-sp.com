\<\?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * <?= $title ?>

 *
 * <?= $description ?>
 *
 * @author a.miwa <miwa@ccrw.co.jp>
 * @version 1.0.0
 * @since 1.0.0     <?= date('Y/m/d') ?>：新規作成
 */
class <?= $className ?> extends CI_Model
{
<?php if (isset($constOnly)) { ?>
    /**
     * const
     */
<?php for ($i = 0, $n = count($constOnly); $i < $n; $i ++) { ?>
    // <?= $constOnly[$i]['title'] ?>

    const <?= $constOnly[$i]['key'] ?> = <?= $constOnly[$i]['val'] ?>;
<?php } ?>
<?php } ?>


<?php if (isset($construct)) { ?>
    /**
     * コントラクト
     */
    public function __construct()
    {
<?php for ($i = 0, $n = count($construct); $i < $n; $i ++) { ?>
        // <?= $construct[$i]['description'] ?>

<?php for ($data_i = 0, $data_n = count($construct[$i]['data']); $data_i < $data_n; $data_i ++) { ?>
        <?= $construct[$i]['data'][$data_i] ?>

<?php } ?>
<?php } ?>
    }
<?php } ?>


    /**
     * 共通テンプレート
     *
     * @param array|null $returnVal：各テンプレート用配列
     * @return array
     */
    public function sharedTemplate(?array $returnVal = array()) : array
    {
<?php if (isset($sharedTemplate)) { ?>
<?php for ($i = 0, $n = count($sharedTemplate); $i < $n; $i ++) { ?>
        // <?= $sharedTemplate[$i]['description'] ?>

<?php for ($data_i = 0, $data_n = count($sharedTemplate[$i]['data']); $data_i < $data_n; $data_i ++) { ?>
        <?= $sharedTemplate[$i]['data'][$data_i] ?>

<?php } ?>
<?php } ?>
<?php } ?>

        return $returnVal;
    }


    /**
     * ログインページテンプレート
     *
     * @param boolean $validFlg
     * @return array|null
     */
    public function LoginTemplate(bool $validFlg = false) : ?array
    {
        // 一覧情報をセット
        $returnVal = array();
        // FORM情報をセット
        foreach ($this->FormDefaultList() as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }

        return $this->sharedTemplate($returnVal);
    }
    /*====================================================================
        関数名： LoginAction
        概　要： ログインページテンプレート情報を取得
    */
    public function LoginAction()
    {
        // 一覧情報をセット
        $returnVal = false;
        // FORM情報をセット
        foreach ($this->FormDefaultList() as $key) {
            $form[$key] = $this->input->post_get($key, true);
        }
        // ログイン処理（SESSION情報を登録）
        $returnVal = $this->login_lib->LoginAction($form['account'], $form['password']);

        return $returnVal;
    }
    /*====================================================================
        関数名： LogoutAction
        概　要： ログアウト処理
    */
    public function LogoutAction()
    {
        // 対象SESSION情報を削除
        $this->login_lib->ClearSessionValues();
    }


    /**
     * フォーム用配列
     *
     * @return array
     */
    public function FormDefaultList() : array
    {
        $returnVal = array(
            'account',
            'password',
        );
        return $returnVal;
    }


    /**
     * エラーチェック配列
     *
     * @return array
     */
    public function ConfigLoginValues() : array
    {
        $returnVal = array(
            array(
                'field'   => 'account',
                'label'   => 'アカウント',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'パスワード',
                'rules'   => 'required|ValidLoginAdmin[' . $this->input->post_get('account', true) . ']'
            ),
        );
        return ($returnVal);
    }
}
