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


<?php if (isset($templateList)) { ?>
<?php foreach ($templateList as $tempKey => $tempVal) { ?>
<?php if (count($tempVal) > 0) { ?>
    /**
     * <?= $tempVal['description'] ?>

     *
<?php for ($arg_i = 0, $arg_n = count($tempVal['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
     * @param <?= $tempVal['arg'][$arg_i]['type'] ?> <?= $tempVal['arg'][$arg_i]['key'] ?>：<?= $tempVal['arg'][$arg_i]['title'] ?>

<?php } ?>
     * @return <?= $tempVal['returnType'] ?>|null
     */
    public function <?= ucfirst($tempKey) ?>Template(<?php
    for ($arg_i = 0, $arg_n = count($tempVal['arg']); $arg_i < $arg_n; $arg_i ++) {
        echo $tempVal['arg'][$arg_i]['type'] . ' ';
        echo $tempVal['arg'][$arg_i]['key'];
        echo($tempVal['arg'][$arg_i]['default'] ? ' = ' . $tempVal['arg'][$arg_i]['default'] : '');
        echo($arg_i < ($arg_n - 1) ? ', ' : ''); ?>
<?php
    }
?>) : ?<?= $tempVal['returnType'] ?>

    {
<?php if (ucfirst($tempKey) == 'List') {/*一覧テンプレートここから*/?>
<?php if (isset($tempVal['iniSet'])) { ?>
<?php for ($ini_i = 0, $ini_n = count($tempVal['iniSet']); $ini_i < $ini_n; $ini_i ++) { ?>
        // <?= $tempVal['iniSet'][$ini_i]['description'] ?>

<?php for ($data_i = 0, $data_n = count($tempVal['iniSet'][$ini_i]['data']); $data_i < $data_n; $data_i ++) { ?>
        <?= $tempVal['iniSet'][$ini_i]['data'][$data_i] ?>

<?php } ?>
<?php } ?>
<?php } ?>

        // 返値を初期化
<?php if ($tempVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($tempVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($tempVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($tempVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>

<?php if (isset($tempVal['library']) && count($tempVal['library']) > 0) { ?>
        // 各ライブラリの読み込み
<?php for ($lib_i = 0, $lib_n = count($tempVal['library']); $lib_i < $lib_n; $lib_i ++) { ?>
        <?= $tempVal['library'][$lib_i] ?>;
<?php } ?>

<?php } ?>
<?php if (isset($tempVal['var'])) { ?>
<?php for ($var_i = 0, $var_n = count($tempVal['var']); $var_i < $var_n; $var_i ++) { ?>
        // <?= $tempVal['var'][$var_i]['description'] ?>

        <?= $tempVal['var'][$var_i]['key'] ?> = <?= $tempVal['var'][$var_i]['val'] ?>

<?php } ?>
<?php } ?>

<?php if (isset($tempVal['selectList'])) { ?>
<?php foreach ($tempVal['selectList']['list'] as $listKey => $listVal) { ?>
        // <?= $tempVal['selectList']['title'] ?>

        $returnVal['select']['<?= $listKey ?>'] = <?= $listVal ?>
<?php } ?>
<?php } ?>

        // FORM情報をセット
        $returnVal['action'] = $this->input->post_get('action', true);
<?php if (isset($tempVal['formList'])) { ?>
        foreach (<?= $tempVal['formList'] ?> as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
<?php } ?>

        // ページ一覧用の情報を取得
        $returnVal['form']['select_count'] = ($returnVal['form']['select_count'] != '' ? $returnVal['form']['select_count'] : Pagenavi_lib::DEFAULT_LIST_COUNT);

<?php if (isset($tempVal['whereSql'])) { ?>
        // WHERE情報をセット
        $whereSql = array();
<?php for ($where_i = 0, $where_n = count($tempVal['whereSql']); $where_i < $where_n; $where_i ++) { ?>
        // <?= $tempVal['whereSql'][$where_i]['title'] ?>

        <?= ($tempVal['whereSql'][$where_i]['if'] ? 'if (' . $tempVal['whereSql'][$where_i]['if'] . ') {' : '') ?>

<?php for ($list_i = 0, $list_n = count($tempVal['whereSql'][$where_i]['list']); $list_i < $list_n; $list_i ++) { ?>
            $whereSql[] = <?= $tempVal['whereSql'][$where_i]['list'][$list_i] ?>

<?php } ?>
        <?= ($tempVal['whereSql'][$where_i]['if'] ? '}' : '') ?>

<?php } ?>
<?php } ?>
<?php if (isset($tempVal['page'])) { ?>
<?php if (isset($tempVal['page']['count'])) { ?>
        // 一覧表示数の取得
        $returnVal['count'] = <?= $tempVal['page']['count'] ?>;
<?php } ?>
<?php if (isset($tempVal['page']['pager'])) { ?>
        // ページナビ情報を取得
        $returnVal['pager'] = <?= $tempVal['page']['pager'] ?>;
<?php } ?>
<?php if (isset($tempVal['page']['limit'])) { ?>
        // LIMIT情報をセット
        $limitSql['begin'] = <?= $tempVal['page']['limit']['begin'] ?>;
        $limitSql['row'] = <?= $tempVal['page']['limit']['row'] ?>;
<?php } ?>
<?php } ?>
<?php if (isset($tempVal['order'])) { ?>
<?php for ($order_i = 0, $order_n = count($tempVal['order']); $order_i < $order_n; $order_i ++) { ?>
        // ORDER情報をセット
        $orderSql[<?= $order_i ?>]['key'] = <?= $tempVal['order'][$order_i]['key'] ?>;
        $orderSql[<?= $order_i ?>]['arrow'] = '<?= $tempVal['order'][$order_i]['arrow'] ?>';
<?php } ?>
<?php } ?>
<?php if (isset($tempVal['getList'])) { ?>
        // 一覧情報を取得
        $returnVal['list'] = <?= $tempVal['getList'] ?>;
<?php } ?>
<?php /*一覧テンプレートここまで*/ ?>
<?php  } elseif (ucfirst($tempKey) == 'Detail') {/*詳細テンプレートここから*/ ?>
        // 返値を初期化
<?php if ($tempVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($tempVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($tempVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($tempVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
        // FORM情報
<?php for ($arg_i = 0, $arg_n = count($tempVal['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
        <?= $tempVal['arg'][$arg_i]['key'] ?> = (<?= $tempVal['arg'][$arg_i]['key'] ?> ? <?= $tempVal['arg'][$arg_i]['key'] ?> : $this->input->post_get('<?= substr($tempVal['arg'][$arg_i]['key'], 1) ?>', true));
<?php } ?>
<?php if (isset($tempVal['exists'])) { ?>
        // 情報の存在有無
        $exists = <?= $tempVal['exists'] ?>;
        $returnVal['exists'] = $exists;
<?php if (isset($tempVal['getData'])) { ?>
        // 受注ID存在
        if ($exists) {
            // 受注詳細情報を取得
            $returnVal['form'] = <?= $tempVal['getData'] ?>;
        }
<?php } ?>
<?php } elseif (isset($tempVal['getData'])) { ?>
        // 受注詳細情報を取得
        $returnVal['form'] = <?= $tempVal['getData'] ?>;
<?php } ?>
<?php /*詳細テンプレートここまで*/ ?>
<?php } elseif (ucfirst($tempKey) == 'Input') {/*入力テンプレートここから*/ ?>
        // 返値を初期化
<?php if ($tempVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($tempVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($tempVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($tempVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
<?php if (isset($tempVal['library']) && count($tempVal['library']) > 0) { ?>
        // 各ライブラリの読み込み
<?php for ($lib_i = 0, $lib_n = count($tempVal['library']); $lib_i < $lib_n; $lib_i ++) { ?>
        <?= $tempVal['library'][$lib_i] ?>;
<?php } ?>

        // FORM情報をセット
        $id = $this->input->post_get('id', true);
        $action = $this->input->post_get('action', true);
<?php if (isset($tempVal['formList'])) { ?>
        foreach (<?= $tempVal['formList'] ?> as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
<?php } ?>
<?php } ?>
<?php if (isset($tempVal['var'])) { ?>
<?php for ($var_i = 0, $var_n = count($tempVal['var']); $var_i < $var_n; $var_i ++) { ?>
        // <?= $tempVal['var'][$var_i]['description'] ?>

        <?= $tempVal['var'][$var_i]['key'] ?> = <?= $tempVal['var'][$var_i]['val'] ?>

<?php } ?>
<?php } ?>
        // 初期画面
        if (
            $action == '' &&
            $exists
        ) {
            // ユーザーIDが存在
            if ($exists) {
                // 受注詳細情報を取得
                $returnVal['form'] = <?= $tempVal['getData'] ?>;
            }
        }
        // 遷移アクション時
        else {
<?php if (isset($tempVal['formList'])) { ?>
            // FORM情報をセット
            foreach (<?= $tempVal['formList'] ?> as $key) {
                $returnVal['form'][$key] = $this->input->post_get($key, true);
            }
<?php } ?>
            // バリデーションOK時
            if ($validFlg) {
<?php if (isset($tempVal['selectName'])) { ?>
                // <?= $tempVal['selectName']['title'] ?>

<?php foreach ($tempVal['selectName']['list'] as $listKey => $listVal) { ?>
                $returnVal['form']['<?= $listKey ?>'] = <?= $listVal ?>;
<?php } ?>
<?php } ?>
            }
        }
<?php if (isset($tempVal['selectList'])) { ?>
        // <?= $tempVal['selectList']['title'] ?>

<?php foreach ($tempVal['selectList']['list'] as $listKey => $listVal) { ?>
        $returnVal['select']['<?= $listKey ?>'] = <?= $listVal ?>;
<?php } ?>
<?php } ?>
<?php /*入力テンプレートここまで*/ ?>
<?php } elseif (ucfirst($tempKey) == 'Comp') {/*完了テンプレートここから*/ ?>
        // 返値を初期化
<?php if ($tempVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($tempVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($tempVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($tempVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
        // FORM情報
<?php for ($arg_i = 0, $arg_n = count($tempVal['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
        <?= $tempVal['arg'][$arg_i]['key'] ?> = (<?= $tempVal['arg'][$arg_i]['key'] ?> ? <?= $tempVal['arg'][$arg_i]['key'] ?> : $this->input->post_get('<?= substr($tempVal['arg'][$arg_i]['key'], 1) ?>', true));
<?php } ?>
<?php if (isset($tempVal['exists'])) { ?>
        // 情報の存在有無
        $returnVal['exists'] = <?= $tempVal['exists'] ?>;
<?php } ?>
<?php }/*完了テンプレートここまで*/ ?>

        return <?= $tempVal['return'] ?>;
    }


<?php } ?>
<?php } ?>
<?php } ?>








<?php if (isset($actionList)) { ?>
<?php foreach ($actionList as $actionKey => $actionVal) { ?>
<?php if (count($actionVal) > 0) { ?>
    /**
     * <?= $actionVal['description'] ?>

     *
<?php for ($arg_i = 0, $arg_n = count($actionVal['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
     * @param <?= $actionVal['arg'][$arg_i]['type'] ?> <?= $actionVal['arg'][$arg_i]['key'] ?>：<?= $actionVal['arg'][$arg_i]['title'] ?>

<?php } ?>
     * @return <?= $actionVal['returnType'] ?>|null
     */
    public function <?= ucfirst($actionKey) ?>(<?php
    for ($arg_i = 0, $arg_n = count($actionVal['arg']); $arg_i < $arg_n; $arg_i ++) {
        echo $actionVal['arg'][$arg_i]['type'] . ' ';
        echo $actionVal['arg'][$arg_i]['key'];
        echo($actionVal['arg'][$arg_i]['default'] ? ' = ' . $actionVal['arg'][$arg_i]['default'] : '');
        echo($arg_i < ($arg_n - 1) ? ', ' : ''); ?>
<?php
    }
?>) : ?<?= $actionVal['returnType'] ?>

    {
<?php if (ucfirst($actionKey) == 'RegistAction') {/*登録処理ここから*/ ?>
        // 返値を初期化
<?php if ($actionVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($actionVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($actionVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($actionVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
<?php if (isset($actionVal['iniSet'])) { ?>
<?php for ($ini_i = 0, $ini_n = count($actionVal['iniSet']); $ini_i < $ini_n; $ini_i ++) { ?>
        // <?= $actionVal['iniSet'][$ini_i]['description'] ?>

<?php for ($data_i = 0, $data_n = count($actionVal['iniSet'][$ini_i]['data']); $data_i < $data_n; $data_i ++) { ?>
        <?= $actionVal['iniSet'][$ini_i]['data'][$data_i] ?>

<?php } ?>
<?php } ?>
<?php } ?>





        // 登録情報の登録・更新
        $returnVal = $this->reserve_lib->Regist($regist, $id);









<?php /*登録処理ここまで*/ ?>
<?php } elseif (ucfirst($actionKey) == 'DelAction') {/*削除処理ここから*/?>
        // 返値を初期化
<?php if ($actionVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($actionVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($actionVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($actionVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>
        // FORM情報をセット
        $id = $this->input->post_get('id', true);
        // 削除処理
        $returnVal = <?= $actionVal['action'] ?>;
<?php /*削除処理ここまで*/ ?>
<?php } elseif (ucfirst($actionKey) == 'GetListCount') {/*一覧合計数取得処理ここから*/?>
        // 返値を初期化
<?php if ($actionVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($actionVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($actionVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($actionVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>


<?php /*一覧合計数取得処理ここまで*/ ?>
<?php } elseif (ucfirst($actionKey) == 'GetList') {/*一覧リスト取得処理ここから*/?>
        // 返値を初期化
<?php if ($actionVal['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($actionVal['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($actionVal['returnType'] == 'int') { ?>
        $returnVal = 0;
<?php } elseif ($actionVal['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>


<?php /*一覧リスト取得処理ここまで*/ ?>
<?php }/*完了ここまで*/ ?>

        return <?= $actionVal['return'] ?>;
    }

<?php } ?>
<?php } ?>
<?php } ?>








<?php if (isset($formList)) { ?>
<?php for ($i = 0, $n = count($formList); $i < $n; $i ++) { ?>
    /**
     * <?= $formList[$i]['description'] ?>

     *
     * @return array
     */
    public function <?= $formList[$i]['key'] ?>() : array
    {
        $returnVal = array(
<?php for ($list_i = 0, $list_n = count($formList[$i]['list']); $list_i < $list_n; $list_i ++) { ?>
            '<?= $formList[$i]['list'][$list_i] ?>',
<?php } ?>
        );
        return $returnVal;
    }


<?php } ?>
<?php } ?>
<?php if (isset($validList)) { ?>
<?php for ($i = 0, $n = count($validList); $i < $n; $i ++) { ?>
    /**
     * <?= $validList[$i]['description'] ?>

     *
     * @return array
     */
    public function <?= $validList[$i]['key'] ?>() : array
    {
        $returnVal = array(
<?php for ($list_i = 0, $list_n = count($validList[$i]['list']); $list_i < $list_n; $list_i ++) { ?>
            array(
                'field'   => '<?= $validList[$i]['list'][$list_i]['field'] ?>',
                'label'   => '<?= $validList[$i]['list'][$list_i]['label'] ?>',
                'rules'   => '<?= $validList[$i]['list'][$list_i]['rules'] ?>'
            ),
<?php } ?>
        );
        return ($returnVal);
    }


<?php } ?>
<?php } ?>
}
