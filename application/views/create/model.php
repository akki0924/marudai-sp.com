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
<?php for ($i = 0, $n = count($templateList); $i < $n; $i ++) { ?>
    /**
     * <?= $templateList[$i]['description'] ?>

     *
<?php for ($arg_i = 0, $arg_n = count($templateList[$i]['arg']); $arg_i < $arg_n; $arg_i ++) { ?>
     * @param <?= $templateList[$i]['arg'][$arg_i]['type'] ?> <?= $templateList[$i]['arg'][$arg_i]['key'] ?>：<?= $templateList[$i]['arg'][$arg_i]['title'] ?>

<?php } ?>
     * @param boolean $validFlg
     * @return <?= $templateList[$i]['returnType'] ?>|null
     */
    public function <?= $templateList[$i]['key'] ?>(<?php
    for ($arg_i = 0, $arg_n = count($templateList[$i]['arg']); $arg_i < $arg_n; $arg_i ++) {
        echo $templateList[$i]['arg'][$arg_i]['type'] . ' ';
        echo $templateList[$i]['arg'][$arg_i]['key'];
        echo($templateList[$i]['arg'][$arg_i]['default'] ? ' = ' . $templateList[$i]['arg'][$arg_i]['default'] : '');
        echo($arg_i < ($arg_n - 1) ? ', ' : ''); ?>
<?php
    }
?>) : ?<?= $templateList[$i]['returnType'] ?>

    {
<?php if (isset($templateList[$i]['iniSet'])) { ?>
<?php for ($ini_i = 0, $ini_n = count($templateList[$i]['iniSet']); $ini_i < $n; $ini_i ++) { ?>
        // <?= $templateList[$i]['iniSet'][$i]['description'] ?>

<?php for ($data_i = 0, $data_n = count($templateList[$i]['iniSet'][$ini_i]['data']); $data_i < $data_n; $data_i ++) { ?>
        <?= $templateList[$i]['iniSet'][$ini_i]['data'][$data_i] ?>

<?php } ?>
<?php } ?>
<?php } ?>

        // 返値を初期化
<?php if ($templateList[$i]['returnType'] == 'array') { ?>
        $returnVal = array();
<?php } elseif ($templateList[$i]['returnType'] == 'string') { ?>
        $returnVal = '';
<?php } elseif ($templateList[$i]['returnType'] == 'bool') { ?>
        $returnVal = false;
<?php } ?>

<?php if (isset($templateList[$i]['library'])) { ?>
        // 各ライブラリの読み込み
<?php for ($lib_i = 0, $lib_n = count($templateList[$i]['library']); $lib_i < $lib_n; $lib_i ++) { ?>
        <?= $templateList[$i]['library'][$lib_i] ?>;
<?php } ?>
<?php } ?>

<?php if (isset($templateList[$i]['var'])) { ?>
<?php for ($var_i = 0, $var_n = count($templateList[$i]['var']); $var_i < $var_n; $var_i ++) { ?>
        // <?= $templateList[$i]['var'][$var_i]['description'] ?>

        <?= $templateList[$i]['var'][$var_i]['key'] ?> = <?= $templateList[$i]['var'][$var_i]['val'] ?>

<?php } ?>
<?php } ?>

<?php if (isset($templateList[$i]['selectList'])) { ?>
<?php foreach ($templateList[$i]['selectList']['list'] as $listKey => $listVal) { ?>
        // <?= $templateList[$i]['selectList']['title'] ?>

        $returnVal['select']['<?= $listKey ?>'] = <?= $listVal ?>
<?php } ?>
<?php } ?>

        // FORM情報をセット
        $returnVal['action'] = $this->input->post_get('action', true);
<?php if (isset($templateList[$i]['formList'])) { ?>
        foreach (<?= $templateList[$i]['formList'] ?> as $key) {
            $returnVal['form'][$key] = $this->input->post_get($key, true);
        }
<?php } ?>

        // ページ一覧用の情報を取得
        $returnVal['form']['select_count'] = ($returnVal['form']['select_count'] != '' ? $returnVal['form']['select_count'] : Pagenavi_lib::DEFAULT_LIST_COUNT);

<?php if (isset($templateList[$i]['list']['whereSql'])) { ?>
        // WHERE情報をセット
        $whereSql = array();
<?php for ($where_i = 0, $where_n = count($templateList[$i]['list']['whereSql']); $where_i < $where_n; $where_i ++) { ?>
        // <?= $templateList[$i]['list']['whereSql'][$where_i]['title'] ?>

        <?= ($templateList[$i]['list']['whereSql'][$where_i]['if'] ? 'if (' . $templateList[$i]['list']['whereSql'][$where_i]['if'] . ') {' : '') ?>

<?php for ($list_i = 0, $list_n = count($templateList[$i]['list']['whereSql'][$where_i]['list']); $list_i < $list_n; $list_i ++) { ?>
            $whereSql[] = <?= $templateList[$i]['list']['whereSql'][$where_i]['list'][$list_i] ?>

<?php } ?>
        <?= ($templateList[$i]['list']['whereSql'][$where_i]['if'] ? '}' : '') ?>

<?php } ?>
<?php } ?>
<?php if (isset($templateList[$i]['list']['page'])) { ?>
<?php if (isset($templateList[$i]['list']['page']['count'])) { ?>
        // 一覧表示数の取得
        $returnVal['count'] = <?= $templateList[$i]['list']['page']['count'] ?>;
<?php } ?>
<?php if (isset($templateList[$i]['list']['page']['pager'])) { ?>
        // ページナビ情報を取得
        $returnVal['pager'] = <?= $templateList[$i]['list']['page']['pager'] ?>;
<?php } ?>
<?php if (isset($templateList[$i]['list']['page']['limit'])) { ?>
        // LIMIT情報をセット
        $limitSql['begin'] = <?= $templateList[$i]['list']['page']['limit']['begin'] ?>;
        $limitSql['row'] = <?= $templateList[$i]['list']['page']['limit']['row'] ?>;
<?php } ?>
<?php } ?>
<?php if (isset($templateList[$i]['list']['order'])) { ?>
<?php for ($order_i = 0, $order_n = count($templateList[$i]['list']['order']); $order_i < $order_n; $order_i ++) { ?>
        // ORDER情報をセット
        $orderSql[<?= $order_i ?>]['key'] = <?= $templateList[$i]['list']['order'][$order_i]['key'] ?>;
        $orderSql[<?= $order_i ?>]['arrow'] = '<?= $templateList[$i]['list']['order'][$order_i]['arrow'] ?>';
<?php } ?>
<?php } ?>
<?php if (isset($templateList[$i]['list']['getList'])) { ?>
        // 一覧情報を取得
        $returnVal['list'] = <?= $templateList[$i]['list']['getList'] ?>;

<?php } ?>
        // FROM値の有無によって表示内容を変更してセット
        $returnVal['no_list_msg'] = self::NO_LIST_MSG;

        return <?= $templateList[$i]['return'] ?>;
    }
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
