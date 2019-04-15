$(function() {
    /**
     * submit処理
     * .submit_action要素をclickし、カスタムデータで値を付与する
     * 以下カスタムデータ
     *  form_id：フォームID
     *  action：フォームアクション
     *  target_○○：クエリ情報
     * @return submit処理するため返値無し
     */
    $(document).on('click', '.submit_action', function() {
        // カスタムデータ属性
        var dataList = $(this).data();
        // カスタムデータ属性がセットされていない場合
        if (Object.keys(dataList).length > 0) {
            // 値をセット用連想配列を宣言
            var dataTargetList = {};
            // ループ（カスタムデータ属性のみ取得）
            $.each(
                dataList,
                function(key, value) {
                    if (key.match(/target_/)) {
                        // dataTargetList[key.split('target_')[1]] = value;
                        dataTargetList[key] = value;
                    }
                }
            );
            // formセレクタ
            if (dataList.form_id) {
                var formSel = $('#' + dataList.form_id);
            } else {
                var formSel = $(this).parents('form');
            }
            // action情報を上書き
            if (dataList.action) {
                formSel.attr('action', dataList.action);
            }
            // クエリ情報をセット
            if (Object.keys(dataTargetList).length > 0) {
                // ループ（カスタムデータ属性：クエリ情報）
                $.each(
                    dataTargetList,
                    function(target_key, target_value) {
                        if (!$("#" + target_key).length) {
                            // hidden情報を動的に追記
                            $('<input>').attr({
                                type: 'hidden',
                                id: target_key,
                                name: target_key,
                                value: target_value
                            }).appendTo('form');
                        } else {
                            // hidden情報をに値をセット
                            $("#" + target_key).val(target_value);
                        }
                    }
                );
            }
            // サブミット処理
            formSel.submit();
        }
    });
});
