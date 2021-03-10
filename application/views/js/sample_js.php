// 読込み完了時
$(function() {
    // ローディングタグ生成
    CreateLorder();
});
// AJAX処理
function AjaxAction(url) {
    $(document).ajaxSend(function() {
        $("<?= $const['sel_loader'] ?>").fadeIn(<?= $const['time_loading_speed'] ?>);
    });
    $.ajax({
        url: '<?= SiteDir(); ?>' + url,
        dataType:'json',
        cache: false
    })
    .then(
        // 成功時
        function (returnData) {
            // 画面への反映フラグ
            if (returnData['<?= $const['key_ajax_reaction_flg'] ?>']) {
                $.each(
                    returnData['<?= $const['key_ajax_reaction'] ?>'],
                    function( key, value ) {
                        var targetSel = ( $('#' + key).length ? '#' + key : '.' + key );
                        $(targetSel).html(value);
                    }
                );
            }
            console.log('loading end1');
            // ローディング解除
            setTimeout(function(){
                $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
            },<?= $const['time_loading_timeout'] ?>);
            console.log('loading end2');
        },
        // エラー時
        function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            // エラー処理
            setTimeout(function(){
                $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
            },<?= $const['time_loading_timeout'] ?>);
        }
    );
}
// ローダー生成
function CreateLorder ()
{
    // ローダー生成
    var loader = $('<div />').attr( 'id', '<?= $const['sel_loader_name'] ?>' )
        .append(
            $('<div />').attr( 'class', '<?= $const['sel_loader_cv_name'] ?>' )
            .append(
                $('<span />').attr( 'class', '<?= $const['sel_loader_spinner_name'] ?>' )
            )
        );
    // BODY要素の最後に追加
    $('body').append(loader);
}
// ローディング表示処理
function LordingStart() {
    $("<?= $const['sel_loader'] ?>").fadeIn(<?= $const['time_loading_speed'] ?>);
}
// ローディング解除処理
function LordingEnd() {
    setTimeout(function(){
        $("<?= $const['sel_loader'] ?>").fadeOut(<?= $const['time_loading_speed'] ?>);
    },<?= $const['time_loading_timeout'] ?>);
}
